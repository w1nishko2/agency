<!DOCTYPE html>
<html>
<head>
    <title>Все страницы в БД</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f5f5f5; }
        .content-map { max-width: 400px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Все страницы в базе данных</h1>
    
    <?php
    require __DIR__ . '/../vendor/autoload.php';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    try {
        $pages = \App\Models\Page::all();
        
        echo '<table>';
        echo '<tr><th>ID</th><th>Title</th><th>Slug</th><th>Content Map</th><th>Is Active</th></tr>';
        
        foreach ($pages as $page) {
            echo '<tr>';
            echo '<td>' . $page->id . '</td>';
            echo '<td>' . htmlspecialchars($page->title) . '</td>';
            echo '<td>' . ($page->slug === '' ? '(пустой - главная)' : htmlspecialchars($page->slug)) . '</td>';
            echo '<td class="content-map">';
            if ($page->content_map && !empty($page->content_map)) {
                echo '<pre style="font-size: 11px;">' . json_encode($page->content_map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            } else {
                echo '<em>пусто</em>';
            }
            echo '</td>';
            echo '<td>' . ($page->is_active ? '✓' : '✗') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        
    } catch (\Exception $e) {
        echo '<div style="background: #fee; padding: 15px; border-radius: 5px;">';
        echo '<strong>Ошибка:</strong> ' . $e->getMessage();
        echo '</div>';
    }
    ?>
</body>
</html>
