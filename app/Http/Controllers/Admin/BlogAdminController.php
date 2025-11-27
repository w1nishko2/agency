<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author']);

        // Поиск
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
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
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_comments'] = $request->has('allow_comments');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $post = BlogPost::create($validated);

        return redirect()->route('admin.blog.edit', $post->id)
            ->with('success', 'Статья успешно создана');
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::all();
        return view('admin.blog.form', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

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
            'slug' => 'required|max:255|unique:blog_posts,slug,' . $id,
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,pending,published',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_comments'] = $request->has('allow_comments');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.blog.edit', $post->id)
            ->with('success', 'Статья успешно обновлена');
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Статья успешно удалена');
    }
}
