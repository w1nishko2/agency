<!DOCTYPE html>
<html>
<head>
    <title>Проверка inline-контента</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background: #fff; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Проверка inline-контента</h1>
    
    <?php
    require __DIR__ . '/../vendor/autoload.php';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    try {
        // Получаем страницу about
        $page = \App\Models\Page::where('slug', 'about')->first();
        
        if (!$page) {
            echo '<div class="debug"><strong>Ошибка:</strong> Страница "about" не найдена</div>';
            exit;
        }
        
        echo '<div class="debug">';
        echo '<h2>Информация о странице</h2>';
        echo '<p><strong>ID:</strong> ' . $page->id . '</p>';
        echo '<p><strong>Slug:</strong> ' . $page->slug . '</p>';
        echo '<p><strong>Title:</strong> ' . $page->title . '</p>';
        echo '</div>';
        
        echo '<div class="debug">';
        echo '<h2>Content Map (сохраненные изменения)</h2>';
        
        if ($page->content_map && !empty($page->content_map)) {
            echo '<pre>' . json_encode($page->content_map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            echo '<p><strong>Количество измененных элементов:</strong> ' . count($page->content_map) . '</p>';
        } else {
            echo '<p>Нет сохраненных изменений</p>';
        }
        echo '</div>';
        
        // Проверяем структуру таблицы
        echo '<div class="debug">';
        echo '<h2>Структура таблицы pages</h2>';
        $columns = \DB::select('SHOW COLUMNS FROM pages');
        echo '<pre>';
        foreach ($columns as $column) {
            echo $column->Field . ' - ' . $column->Type . "\n";
        }
        echo '</pre>';
        echo '</div>';
        
    } catch (\Exception $e) {
        echo '<div class="debug" style="background: #fee;">';
        echo '<strong>Ошибка:</strong> ' . $e->getMessage();
        echo '</div>';
    }
    ?>
    
    <div class="debug">
        <h2>Инструкции</h2>
        <ol>
            <li>Откройте <a href="/pages/about/inline-edit" target="_blank">/pages/about/inline-edit</a></li>
            <li>Кликните на любой текст и отредактируйте его</li>
            <li>Нажмите "Сохранить"</li>
            <li>Обновите эту страницу</li>
            <li>Проверьте, появились ли изменения в Content Map выше</li>
        </ol>
    </div>
</body>
</html>
