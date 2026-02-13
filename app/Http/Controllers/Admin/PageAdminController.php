<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageAdminController extends Controller
{
    /**
     * Отображение списка страниц
     */
    public function index()
    {
        $pages = Page::orderBy('slug')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Отображение формы редактирования страницы
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Обновление страницы
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'about_image_1' => 'nullable|image',
            'about_image_2' => 'nullable|image',
            'about_image_3' => 'nullable|image',
            'about_image_4' => 'nullable|image',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Обработка главного изображения (Hero)
        if ($request->hasFile('image')) {
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
            }
            $validated['image'] = $this->processImage($request->file('image'), 'pages', 1920, 1080);
        }

        // Обработка дополнительных изображений
        $images = $page->images ?? [];
        
        for ($i = 1; $i <= 4; $i++) {
            $key = "about_image_{$i}";
            if ($request->hasFile($key)) {
                // Удаляем старое изображение если есть
                if (isset($images[$key]) && Storage::disk('public')->exists($images[$key])) {
                    Storage::disk('public')->delete($images[$key]);
                }
                $images[$key] = $this->processImage($request->file($key), 'pages', 1200, 800);
            }
        }
        
        $validated['images'] = $images;
        $validated['is_active'] = $request->has('is_active');

        $page->update($validated);

        return redirect()
            ->route('admin.pages.edit', $page->id)
            ->with('success', 'Страница успешно обновлена!');
    }

    /**
     * Удаление изображения страницы
     */
    public function deleteImage($id, Request $request)
    {
        $page = Page::findOrFail($id);
        $imageType = $request->input('type', 'hero');

        if ($imageType === 'hero') {
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
                $page->image = null;
                $page->save();
            }
        } else {
            // Удаление дополнительного изображения
            $images = $page->images ?? [];
            if (isset($images[$imageType]) && Storage::disk('public')->exists($images[$imageType])) {
                Storage::disk('public')->delete($images[$imageType]);
                unset($images[$imageType]);
                $page->images = $images;
                $page->save();
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Обрезка изображения страницы
     */
    public function cropImage(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        
        $request->validate([
            'image' => 'required|image',
            'type' => 'required|string'
        ]);

        $imageType = $request->input('type');
        $file = $request->file('image');
        
        // Определяем папку для сохранения
        $folder = 'pages';
        
        // Обрабатываем и сохраняем изображение
        $path = $this->processImage($file, $folder);
        
        // Удаляем старое изображение
        if ($imageType === 'hero') {
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
            }
            $page->image = $path;
        } else {
            // Для дополнительных изображений
            $images = $page->images ?? [];
            if (isset($images[$imageType]) && Storage::disk('public')->exists($images[$imageType])) {
                Storage::disk('public')->delete($images[$imageType]);
            }
            $images[$imageType] = $path;
            $page->images = $images;
        }
        
        $page->save();

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Обработка изображения: конвертация в WebP, изменение размера, сжатие
     */
    private function processImage($file, $folder, $maxWidth = 1920, $maxHeight = 1080)
    {
        // Создаем менеджер изображений (Intervention Image 3.x)
        $manager = new \Intervention\Image\ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );
        
        // Читаем изображение
        $image = $manager->read($file);
        
        // Изменяем размер с сохранением пропорций (не увеличиваем)
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scale(width: $maxWidth, height: $maxHeight);
        }
        
        // Генерируем уникальное имя файла
        $filename = uniqid() . '.webp';
        $path = "{$folder}/{$filename}";
        
        // Конвертируем в WebP с качеством 85%
        $encoded = $image->toWebp(quality: 85);
        
        // Сохраняем в storage/app/public
        \Storage::disk('public')->put($path, (string) $encoded);
        
        return $path;
    }
}
