@extends('layouts.main')

@section('title', $post->title . ' - Golden Models')
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))

@push('meta')
<!-- Open Graph -->
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
@if($post->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
@endif
<meta property="og:url" content="{{ url()->current() }}">
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
@if($post->author)
<meta property="article:author" content="{{ $post->author->name }}">
@endif
@if($post->category)
<meta property="article:section" content="{{ $post->category->name }}">
@endif
@endpush

@section('content')

<!-- Статья -->
<article class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Хлебные крошки -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none text-dark">Блог</a></li>
                        @if($post->category)
                        <li class="breadcrumb-item"><a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="text-decoration-none text-dark">{{ $post->category->name }}</a></li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
                    </ol>
                </nav>

                <!-- Заголовок -->
                <header class="mb-4">
                    @if($post->category)
                    <span class="badge bg-dark mb-3">{{ $post->category->name }}</span>
                    @endif
                    <h1 class="mb-3">{{ $post->title }}</h1>
                    <div class="d-flex align-items-center text-muted mb-4 flex-wrap">
                        @if($post->author)
                        <span class="me-3">
                            <i class="bi bi-person me-1"></i>
                            {{ $post->author->name }}
                        </span>
                        @endif
                        <i class="bi bi-calendar3 me-2"></i>
                        <span class="me-3">{{ $post->published_at->format('d.m.Y') }}</span>
                        <i class="bi bi-clock me-2"></i>
                        <span class="me-3">{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} мин чтения</span>
                        <i class="bi bi-eye me-2"></i>
                        <span>{{ number_format($post->views_count ?? 0, 0, ',', ' ') }} {{ trans_choice('просмотр|просмотра|просмотров', $post->views_count ?? 0) }}</span>
                    </div>
                </header>

                <!-- Главное изображение -->
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                     class="img-fluid mb-5" 
                     alt="{{ $post->title }}"
                     loading="lazy">
                @endif

                <!-- Содержание -->
                <div class="article-content">
                    @if($post->excerpt)
                    <p class="lead">
                        {{ $post->excerpt }}
                    </p>
                    @endif

                    {!! $post->content !!}
                </div>

                <!-- Фотогалерея -->
                @if($post->gallery_images && is_array($post->gallery_images) && count($post->gallery_images) > 0)
                <div class="my-5">
                    <h3 class="mb-4">Галерея</h3>
                    <div class="row g-3">
                        @foreach($post->gallery_images as $index => $image)
                        <div class="col-md-{{ count($post->gallery_images) == 1 ? '12' : (count($post->gallery_images) == 2 ? '6' : '4') }}">
                            <a href="{{ asset('storage/' . $image) }}" 
                               data-lightbox="gallery" 
                               data-title="{{ $post->title }}">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="img-fluid w-100" 
                                     alt="Галерея {{ $index + 1 }}"
                                     loading="lazy"
                                     style="height: 300px; object-fit: cover; cursor: pointer;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Теги -->
                @if($post->tags && count($post->tags) > 0)
                <div class="mt-5 mb-4">
                    <span class="text-uppercase text-muted small me-2">Теги:</span>
                    @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="badge bg-light text-dark text-decoration-none me-2">{{ $tag }}</a>
                    @endforeach
                </div>
                @endif

                <!-- Поделиться -->
                <div class="d-flex align-items-center border-top border-bottom py-4 mb-5">
                    <span class="text-uppercase text-muted small me-3">Поделиться:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="btn btn-outline-dark btn-sm me-2"
                       title="Поделиться в Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://vk.com/share.php?url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="btn btn-outline-dark btn-sm me-2"
                       title="Поделиться ВКонтакте">
                        <i class="bi bi-globe"></i>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
                       target="_blank" 
                       class="btn btn-outline-dark btn-sm me-2"
                       title="Поделиться в Telegram">
                        <i class="bi bi-telegram"></i>
                    </a>
                    <button onclick="copyToClipboard('{{ url()->current() }}')" 
                            class="btn btn-outline-dark btn-sm"
                            title="Скопировать ссылку">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                </div>

                <!-- Похожие статьи -->
                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div class="mt-5">
                    <h3 class="mb-4">Читайте также</h3>
                    <div class="row g-4">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-6">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none text-dark">
                                @if($relatedPost->featured_image)
                                <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" 
                                     class="img-fluid mb-3" 
                                     style="aspect-ratio: 16/9; object-fit: cover;"
                                     alt="{{ $relatedPost->title }}"
                                     loading="lazy">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                                     style="aspect-ratio: 16/9;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                                @endif
                                @if($relatedPost->category)
                                <span class="badge bg-dark mb-2">{{ $relatedPost->category->name }}</span>
                                @endif
                                <h5>{{ Str::limit($relatedPost->title, 60) }}</h5>
                                <p class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $relatedPost->published_at->format('d.m.Y') }}
                                </p>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</article>

@endsection

@push('styles')
<!-- Lightbox для галереи -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<!-- Quill стили для корректного отображения контента -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
.article-content p {
    margin-bottom: 1.5rem;
}
.article-content h1 {
    font-size: 2.5rem;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
}
.article-content h2 {
    font-size: 2rem;
    margin-top: 3rem;
    margin-bottom: 1rem;
    font-weight: 600;
}
.article-content h3 {
    font-size: 1.75rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}
.article-content h4 {
    font-size: 1.5rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.article-content h5 {
    font-size: 1.25rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.article-content h6 {
    font-size: 1.1rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}
.article-content ul li,
.article-content ol li {
    margin-bottom: 0.5rem;
}
.article-content img {
    max-width: 100%;
    height: auto;
    margin: 2rem 0;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.article-content blockquote {
    border-left: 4px solid #000;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #666;
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 4px;
}
.article-content pre {
    background: #f4f4f4;
    padding: 1rem;
    border-radius: 4px;
    overflow-x: auto;
    margin: 1.5rem 0;
}
.article-content code {
    background: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}
.article-content pre code {
    background: transparent;
    padding: 0;
}
.article-content a {
    color: #007bff;
    text-decoration: underline;
}
.article-content a:hover {
    color: #0056b3;
}
.article-content strong {
    font-weight: 700;
}
.article-content em {
    font-style: italic;
}
.article-content u {
    text-decoration: underline;
}
.article-content s,
.article-content strike {
    text-decoration: line-through;
}
/* Выравнивание текста из Quill */
.article-content .ql-align-center {
    text-align: center;
}
.article-content .ql-align-right {
    text-align: right;
}
.article-content .ql-align-justify {
    text-align: justify;
}
/* Отступы для списков из Quill */
.article-content .ql-indent-1 {
    padding-left: 3em;
}
.article-content .ql-indent-2 {
    padding-left: 6em;
}
.article-content .ql-indent-3 {
    padding-left: 9em;
}
</style>
@endpush

@push('scripts')
<!-- Lightbox для галереи -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    // Копирование ссылки в буфер обмена
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Ссылка скопирована в буфер обмена!');
        }, function(err) {
            console.error('Ошибка копирования: ', err);
        });
    }
    
    // Настройки Lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Изображение %1 из %2'
    });
</script>
@endpush
