<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер для inline-редактирования контента на страницах
 */
class InlineEditController extends Controller
{
    /**
     * Отображение страницы в режиме inline-редактирования
     */
    public function showPage($slug)
    {
        // Получаем страницу по slug (пустой slug = главная)
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        // Определяем какой view использовать
        $viewName = $this->getViewName($slug);
        
        // Получаем дополнительные данные в зависимости от страницы
        $data = $this->getPageData($slug);
        $data['page'] = $page;
        
        return view($viewName, $data);
    }

    /**
     * Сохранение изменений контента
     */
    public function updateContent(Request $request)
    {
        // Проверяем права доступа
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }

        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'element_id' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $page = Page::findOrFail($validated['page_id']);
            
            // Получаем текущий контент страницы
            $contentMap = $page->content_map ?? [];
            
            // Сохраняем обновленный контент для конкретного элемента
            $contentMap[$validated['element_id']] = $validated['content'];
            
            $page->content_map = $contentMap;
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Контент успешно обновлен',
                'data' => [
                    'element_id' => $validated['element_id'],
                    'content' => $validated['content']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Ошибка при сохранении: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получить сохраненный контент для страницы (публичный метод)
     */
    public function getPublicPageContent($pageId)
    {
        $page = Page::findOrFail($pageId);
        return response()->json([
            'success' => true,
            'content_map' => $page->content_map ?? []
        ]);
    }
    
    /**
     * Получить сохраненный контент для страницы (для админа)
     */
    public function getPageContent($pageId)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $page = Page::findOrFail($pageId);
        return response()->json([
            'success' => true,
            'content_map' => $page->content_map ?? []
        ]);
    }

    /**
     * Определяет имя view для страницы
     */
    private function getViewName($slug)
    {
        switch ($slug) {
            case '':
                return 'index';
            case 'about':
                return 'about';
            case 'contacts':
                return 'contacts';
            default:
                return 'page';
        }
    }

    /**
     * Получает дополнительные данные для страницы
     */
    private function getPageData($slug)
    {
        $data = [];
        
        switch ($slug) {
            case '':
                // Для главной страницы получаем последние посты
                $data['latestPosts'] = \App\Models\BlogPost::published()
                    ->orderBy('published_at', 'desc')
                    ->limit(3)
                    ->get();
                $data['homePage'] = Page::where('slug', '')->where('is_active', true)->first();
                break;
            case 'about':
                $data['aboutPage'] = Page::where('slug', 'about')->where('is_active', true)->first();
                break;
            case 'contacts':
                $data['contactsPage'] = Page::where('slug', 'contacts')->where('is_active', true)->first();
                break;
        }
        
        return $data;
    }
}
