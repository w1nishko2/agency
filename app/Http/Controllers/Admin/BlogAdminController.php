<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogAdminController extends Controller
{
    public function index(Request $request)
    {
        // Валидация параметров фильтров
        $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,pending,published',
            'category' => 'nullable|integer|exists:blog_categories,id'
        ]);
        
        $query = BlogPost::with(['category', 'author']);

        // Поиск с экранированием спецсимволов LIKE
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $posts = $query->latest()->paginate(20);
        $categories = BlogCategory::all();

        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.form', compact('categories'));
    }

    public function store(Request $request)
    {
        // Преобразуем строку "null" в настоящий null перед валидацией
        $data = $request->all();
        if (isset($data['category_id']) && ($data['category_id'] === 'null' || $data['category_id'] === '')) {
            $data['category_id'] = null;
        }
        if (isset($data['excerpt']) && ($data['excerpt'] === 'null' || $data['excerpt'] === '')) {
            $data['excerpt'] = null;
        }
        $request->merge($data);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:blog_posts,slug|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,pending,published',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|max:70',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_comments'] = $request->has('allow_comments');
        
        // Санитизация content (удаление опасных тегов, но сохраняем форматирование Quill)
        if (!empty($validated['content'])) {
            $validated['content'] = strip_tags(
                $validated['content'], 
                '<p><br><strong><em><u><s><strike><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><code><pre><img><span><div>'
            );
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
            }

            // Обработка галереи
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $image->store('blog/gallery', 'public');
                }
                $validated['gallery_images'] = json_encode($galleryPaths);
            }

            // Обработка тегов и ключевых слов
            if (!empty($validated['tags'])) {
                $validated['tags'] = json_encode(array_map('trim', explode(',', $validated['tags'])));
            }
            if (!empty($validated['meta_keywords'])) {
                $validated['meta_keywords'] = json_encode(array_map('trim', explode(',', $validated['meta_keywords'])));
            }

            $post = BlogPost::create($validated);
            
            DB::commit();
            
            Log::info('Blog post created', [
                'post_id' => $post->id,
                'title' => $post->title,
                'status' => $post->status,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            return redirect()->route('admin.blog.edit', $post->id)
                ->with('success', 'Статья успешно создана');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Удаляем загруженные файлы при ошибке
            if (isset($validated['featured_image']) && Storage::disk('public')->exists($validated['featured_image'])) {
                Storage::disk('public')->delete($validated['featured_image']);
            }
            if (isset($galleryPaths)) {
                foreach ($galleryPaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            
            Log::error('Blog post creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id()
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Ошибка при создании статьи: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $post = BlogPost::findOrFail($validated['id']);
        $categories = BlogCategory::all();
        return view('admin.blog.form', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $post = BlogPost::findOrFail($validated_id['id']);

        // Преобразуем строку "null" в настоящий null перед валидацией
        $data = $request->all();
        if (isset($data['category_id']) && ($data['category_id'] === 'null' || $data['category_id'] === '')) {
            $data['category_id'] = null;
        }
        if (isset($data['excerpt']) && ($data['excerpt'] === 'null' || $data['excerpt'] === '')) {
            $data['excerpt'] = null;
        }
        $request->merge($data);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:blog_posts,slug,' . $validated_id['id'],
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,pending,published',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|max:70',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_comments'] = $request->has('allow_comments');
        
        // Санитизация content (удаление опасных тегов, но сохраняем форматирование Quill)
        if (!empty($validated['content'])) {
            $validated['content'] = strip_tags(
                $validated['content'], 
                '<p><br><strong><em><u><s><strike><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><code><pre><img><span><div>'
            );
        }

        DB::beginTransaction();
        try {
            $oldFeaturedImage = $post->featured_image;
            $oldGalleryImages = $post->gallery_images;
            
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
                
                // Удаляем старое изображение
                if ($oldFeaturedImage && Storage::disk('public')->exists($oldFeaturedImage)) {
                    Storage::disk('public')->delete($oldFeaturedImage);
                }
            }

            // Обработка галереи
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $image->store('blog/gallery', 'public');
                }
                $validated['gallery_images'] = json_encode($galleryPaths);
                
                // Удаляем старые изображения галереи
                if ($oldGalleryImages && is_array($oldGalleryImages)) {
                    foreach ($oldGalleryImages as $oldImage) {
                        if (Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                }
            }

            // Обработка тегов и ключевых слов
            if (!empty($validated['tags'])) {
                $validated['tags'] = json_encode(array_map('trim', explode(',', $validated['tags'])));
            }
            if (!empty($validated['meta_keywords'])) {
                $validated['meta_keywords'] = json_encode(array_map('trim', explode(',', $validated['meta_keywords'])));
            }

            $post->update($validated);
            
            DB::commit();
            
            Log::info('Blog post updated', [
                'post_id' => $post->id,
                'title' => $post->title,
                'status' => $post->status,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            return redirect()->route('admin.blog.edit', $post->id)
                ->with('success', 'Статья успешно обновлена');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Удаляем новые файлы при ошибке
            if (isset($validated['featured_image']) && $validated['featured_image'] !== $oldFeaturedImage) {
                if (Storage::disk('public')->exists($validated['featured_image'])) {
                    Storage::disk('public')->delete($validated['featured_image']);
                }
            }
            if (isset($galleryPaths)) {
                foreach ($galleryPaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            
            Log::error('Blog post update failed', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Ошибка при обновлении статьи']);
        }
    }

    public function destroy($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $post = BlogPost::findOrFail($validated['id']);
        
        $postData = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status
        ];
        
        DB::beginTransaction();
        try {
            // Удаляем файлы
            if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            if ($post->gallery_images && is_array($post->gallery_images)) {
                foreach ($post->gallery_images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            $post->delete();
            
            DB::commit();
            
            Log::warning('Blog post deleted', array_merge($postData, [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]));

            return redirect()->route('admin.blog.index')
                ->with('success', 'Статья успешно удалена');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Blog post deletion failed', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return back()->withErrors(['error' => 'Ошибка при удалении статьи']);
        }
    }
}
