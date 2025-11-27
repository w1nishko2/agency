@extends('layouts.admin')

@section('title', isset($post) ? 'Редактировать статью' : 'Создать статью')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Блог</a></li>
    <li class="breadcrumb-item active">{{ isset($post) ? 'Редактирование' : 'Создание' }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($post) ? 'Редактировать статью' : 'Создать статью' }}</h2>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Назад
    </a>
</div>

<form action="{{ isset($post) ? route('admin.blog.update', $post->id) : route('admin.blog.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @if(isset($post))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Левая колонка - основной контент -->
        <div class="col-lg-8">
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0">Основная информация</h5>
                </div>
                <div class="content-card-body">

                                <!-- Заголовок -->
                                <div class="mb-4">
                                    <label for="title" class="form-label">Заголовок *</label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $post->title ?? '') }}" 
                                           required
                                           maxlength="255">
                                </div>

                                <!-- Slug -->
                                <div class="mb-4">
                                    <label for="slug" class="form-label">ЧПУ (slug) *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug', $post->slug ?? '') }}" 
                                           required
                                           pattern="[a-z0-9-]+"
                                           maxlength="255">
                                    <small class="text-muted">Только латиница, цифры и дефис</small>
                                </div>

                                <!-- Краткое описание -->
                                <div class="mb-4">
                                    <label for="excerpt" class="form-label">Краткое описание</label>
                                    <textarea class="form-control" 
                                              id="excerpt" 
                                              name="excerpt" 
                                              rows="3"
                                              maxlength="500">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                                    <small class="text-muted">Будет отображаться в превью статьи</small>
                                </div>

                                <!-- Основной текст -->
                                <div class="mb-4">
                                    <label for="content" class="form-label">Содержание *</label>
                                    <textarea class="form-control" 
                                              id="content" 
                                              name="content" 
                                              rows="20" 
                                              required>{{ old('content', $post->content ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Медиа -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0">Медиа</h5>
                            </div>
                            <div class="content-card-body">

                                <!-- Обложка -->
                                <div class="mb-4">
                                    <label for="featured_image" class="form-label">Обложка статьи</label>
                                    @if(isset($post) && $post->featured_image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                                 alt="Текущая обложка" 
                                                 class="img-thumbnail"
                                                 style="max-width: 300px;">
                                        </div>
                                    @endif
                                    <input type="file" 
                                           class="form-control" 
                                           id="featured_image" 
                                           name="featured_image" 
                                           accept="image/*">
                                    <small class="text-muted">Рекомендуемый размер: 1200x800px</small>
                                </div>

                                <!-- Галерея -->
                                <div class="mb-4">
                                    <label for="gallery_images" class="form-label">Галерея изображений</label>
                                    @if(isset($post) && $post->gallery_images && count($post->gallery_images) > 0)
                                        <div class="row g-2 mb-3">
                                            @foreach($post->gallery_images as $image)
                                                <div class="col-auto">
                                                    <img src="{{ asset('storage/' . $image) }}" 
                                                         alt="Галерея" 
                                                         class="img-thumbnail"
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <input type="file" 
                                           class="form-control" 
                                           id="gallery_images" 
                                           name="gallery_images[]" 
                                           multiple 
                                           accept="image/*">
                                    <small class="text-muted">Можно загрузить несколько изображений</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0">SEO</h5>
                            </div>
                            <div class="content-card-body">

                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title', $post->meta_title ?? '') }}"
                                           maxlength="70">
                                    <small class="text-muted">Оптимально: 50-60 символов</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" 
                                              id="meta_description" 
                                              name="meta_description" 
                                              rows="3"
                                              maxlength="160">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                                    <small class="text-muted">Оптимально: 120-160 символов</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="meta_keywords" 
                                           name="meta_keywords" 
                                           value="{{ old('meta_keywords', is_array($post->meta_keywords ?? '') ? implode(', ', $post->meta_keywords) : '') }}"
                                           placeholder="ключевое слово 1, ключевое слово 2">
                                    <small class="text-muted">Разделяйте запятыми</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Правая колонка - метаданные -->
                    <div class="col-lg-4">
                        <!-- Публикация -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0">Публикация</h5>
                            </div>
                            <div class="content-card-body">

                                <div class="mb-3">
                                    <label for="status" class="form-label">Статус *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" {{ old('status', $post->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                                            Черновик
                                        </option>
                                        <option value="pending" {{ old('status', $post->status ?? '') == 'pending' ? 'selected' : '' }}>
                                            На модерации
                                        </option>
                                        <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>
                                            Опубликовано
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Дата публикации</label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="published_at" 
                                           name="published_at" 
                                           value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_featured" 
                                           name="is_featured" 
                                           value="1"
                                           {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Избранная статья
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="allow_comments" 
                                           name="allow_comments" 
                                           value="1"
                                           {{ old('allow_comments', $post->allow_comments ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_comments">
                                        Разрешить комментарии
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Категория -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0">Категория</h5>
                            </div>
                            <div class="content-card-body">

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Категория</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Без категории</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Теги -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0">Теги</h5>
                            </div>
                            <div class="content-card-body">

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Теги</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="tags" 
                                           name="tags" 
                                           value="{{ old('tags', isset($post->tags) && is_array($post->tags) ? implode(', ', $post->tags) : '') }}"
                                           placeholder="тег1, тег2, тег3">
                                    <small class="text-muted">Разделяйте запятыми</small>
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i>
                                {{ isset($post) ? 'Сохранить изменения' : 'Создать статью' }}
                            </button>
                            @if(isset($post))
                                <a href="{{ route('blog.show', $post->slug) }}" 
                                   class="btn btn-outline-secondary"
                                   target="_blank">
                                    <i class="bi bi-eye me-2"></i>
                                    Просмотр на сайте
                                </a>
                            @endif
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">
                                Отмена
                            </a>
                        </div>
                    </div>
                </div>
            </form>

@endsection

@push('scripts')
<script>
    // Автоматическая генерация slug из заголовка
    document.getElementById('title').addEventListener('input', function() {
        const slug = document.getElementById('slug');
        if (!slug.dataset.manual) {
            const transliterated = transliterate(this.value);
            slug.value = transliterated
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }
    });
    
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
    
    function transliterate(text) {
        const ru = {
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 
            'ж': 'zh', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 
            'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 
            'ф': 'f', 'х': 'h', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch', 'ъ': '', 
            'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
        };
        
        return text.split('').map(char => ru[char.toLowerCase()] || char).join('');
    }
</script>
@endpush
