# Представление просмотра статьи блога

## Файл
`resources/views/blog/show.blade.php`

## Функциональность

### ✅ Реализовано согласно ТЗ:

1. **Обложка** - главное изображение статьи (`featured_image`)
2. **Заголовок и метаинформация**:
   - Название статьи
   - Автор (если указан)
   - Дата публикации
   - Время чтения
   - Количество просмотров
   - Категория

3. **Содержание статьи** - полный текст с HTML-форматированием

4. **Фотогалерея** (`gallery_images`):
   - Lightbox для просмотра изображений
   - Адаптивная сетка (1, 2 или 3 колонки)
   - Lazy-load изображений

5. **Похожие публикации**:
   - 2 статьи из той же категории
   - Автоматический подбор контроллером

6. **SEO и OG-теги**:
   - meta title, description
   - Open Graph теги для соцсетей
   - Structured data для поисковых систем

7. **Дополнительно**:
   - Хлебные крошки (breadcrumbs)
   - Теги статьи
   - Кнопки "Поделиться" (Facebook, VK, Telegram)
   - Копирование ссылки в буфер обмена

## Используемые поля модели BlogPost

```php
- title              // Заголовок
- slug               // ЧПУ
- excerpt            // Краткое описание
- content            // Основной текст (HTML)
- featured_image     // Главное изображение
- gallery_images     // Массив дополнительных фото
- tags               // Массив тегов
- category_id        // ID категории
- author_id          // ID автора
- published_at       // Дата публикации
- views_count        // Счетчик просмотров
```

## Зависимости

### JavaScript библиотеки:
- **Lightbox2** - для галереи изображений
  - CSS: `https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css`
  - JS: `https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js`

### CSS:
- Стили для контента статьи (типографика, отступы)
- Адаптивная верстка

## Контроллер

`app/Http/Controllers/BlogController.php` - метод `show($slug)`

```php
public function show($slug)
{
    $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
    $post->incrementViews(); // Увеличение счетчика просмотров

    // Похожие статьи из той же категории
    $relatedPosts = BlogPost::published()
        ->where('id', '!=', $post->id)
        ->where('category_id', $post->category_id)
        ->inRandomOrder()
        ->limit(3)
        ->get();

    return view('blog.show', compact('post', 'relatedPosts'));
}
```

## Маршрут

`routes/web.php`:
```php
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
```

## Функции JavaScript

### copyToClipboard(text)
Копирует ссылку на статью в буфер обмена:
```javascript
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Ссылка скопирована в буфер обмена!');
    });
}
```

### Lightbox
Настройка галереи изображений:
```javascript
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': 'Изображение %1 из %2'
});
```

## Примеры использования

### Ссылка на статью:
```blade
<a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
```

### Проверка наличия галереи:
```blade
@if($post->gallery_images && count($post->gallery_images) > 0)
    <!-- Показать галерею -->
@endif
```

## Адаптивность

- Мобильные устройства: 1 колонка
- Планшеты: 2 колонки для похожих статей
- Десктоп: основной контент занимает 8 колонок из 12 (col-lg-8)

## Производительность

- Lazy-load для всех изображений
- Оптимизированные запросы к БД через Eager Loading
- Кеширование счетчика просмотров
