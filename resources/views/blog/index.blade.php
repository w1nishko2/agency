@extends('layouts.main')

@section('title', 'Блог - Golden Models')
@section('description', 'Новости модельного бизнеса, советы моделям, тренды индустрии от Golden Models.')

@section('content')

<!-- Hero -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo/photo_5_2026-01-24_11-43-44.webp') }}') center/cover;">
    <div class="container">
        <h1 class="mb-3">БЛОГ</h1>
        <p class="lead text-muted">Новости, советы и тренды модельной индустрии</p>
    </div>
</section>

<!-- Блог -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            
            <!-- Статьи -->
            <div class="col-lg-8">
                
                @if($posts->count() > 0)
                    @php $firstPost = $posts->first(); @endphp
                    
                    <!-- Главная статья -->
                    <article class="mb-5">
                        <a href="{{ route('blog.show', $firstPost->slug) }}" class="text-decoration-none text-dark">
                            <div class="row g-0">
                                <div class="col-md-6">
                                    @if($firstPost->featured_image)
                                        <img src="{{ asset('storage/' . $firstPost->featured_image) }}" 
                                             class="img-fluid h-100" 
                                             style="object-fit: cover;"
                                             alt="{{ $firstPost->title }}"
                                             loading="lazy">
                                    @else
                                        <img src="{{ asset('imgsite/photo/photo_5_2026-01-24_11-43-44.webp') }}" 
                                             class="img-fluid h-100" 
                                             style="object-fit: cover;"
                                             alt="{{ $firstPost->title }}"
                                             loading="lazy">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4">
                                        @if($firstPost->category)
                                            <span class="badge bg-dark mb-2">{{ $firstPost->category->name }}</span>
                                        @endif
                                        <h2 class="mb-3">{{ $firstPost->title }}</h2>
                                        <p class="text-muted mb-3">
                                            {{ Str::limit($firstPost->excerpt ?? strip_tags($firstPost->content), 200) }}
                                        </p>
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar3 me-2"></i>{{ $firstPost->published_at->format('d.m.Y') }}
                                            @if($firstPost->read_time)
                                            <span class="mx-2">•</span>
                                            <i class="bi bi-clock me-2"></i>{{ $firstPost->read_time }} мин чтения
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>

                    <hr class="my-5">

                    <!-- Сетка статей -->
                    <div class="row g-4">
                        @foreach($posts->skip(1) as $post)
                        <div class="col-md-6">
                            <article>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                    <div class="row g-0">
                                        <div class="col-5">
                                            @if($post->featured_image)
                                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                                     class="img-fluid h-100" 
                                                     style="object-fit: cover;"
                                                     alt="{{ $post->title }}"
                                                     loading="lazy">
                                            @else
                                                <img src="{{ asset('imgsite/photo/photo_6_2026-01-24_11-43-44.webp') }}" 
                                                     class="img-fluid h-100" 
                                                     style="object-fit: cover;"
                                                     alt="{{ $post->title }}"
                                                     loading="lazy">
                                            @endif
                                        </div>
                                        <div class="col-7">
                                            <div class="p-3">
                                                @if($post->category)
                                                    <span class="badge bg-dark mb-2">{{ $post->category->name }}</span>
                                                @endif
                                                <h4 class="mb-2">{{ $post->title }}</h4>
                                                <p class="text-muted small mb-2">
                                                    {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                                                </p>
                                                <div class="small text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>{{ $post->published_at->format('d.m.Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">Статьи скоро появятся</p>
                    </div>
                @endif

                <!-- Пагинация -->
                @if($posts->hasPages())
                <nav aria-label="Навигация по страницам" class="mt-5">
                    {{ $posts->links() }}
                </nav>
                @endif

            </div>

            <!-- Сайдбар -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    
                    <!-- Поиск -->
                    <div class="mb-5">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Поиск..." value="{{ request('search') }}">
                                <button class="btn btn-dark" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Категории -->
                    @if($categories->count() > 0)
                    <div class="mb-5">
                        <h5 class="text-uppercase mb-3">Категории</h5>
                        <div class="list-group list-group-flush">
                            @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                               class="list-group-item list-group-item-action border-0 px-0 {{ request('category') == $category->slug ? 'active' : '' }}">
                                {{ $category->name }} 
                                <span class="badge bg-light text-dark float-end">{{ $category->published_posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Популярные статьи -->
                    @if(isset($popularPosts) && $popularPosts->count() > 0)
                    <div class="mb-5">
                        <h5 class="text-uppercase mb-3">Популярное</h5>
                        @foreach($popularPosts as $popularPost)
                        <div class="d-flex mb-3">
                            @if($popularPost->featured_image)
                                <img src="{{ asset('storage/' . $popularPost->featured_image) }}" 
                                     class="me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     alt="{{ $popularPost->title }}">
                            @else
                                <img src="{{ asset('imgsite/photo/photo_7_2026-01-24_11-43-44.webp') }}" 
                                     class="me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     alt="{{ $popularPost->title }}">
                            @endif
                            <div>
                                <a href="{{ route('blog.show', $popularPost->slug) }}" class="text-decoration-none text-dark">
                                    <h6 class="mb-1">{{ Str::limit($popularPost->title, 50) }}</h6>
                                </a>
                                <small class="text-muted">{{ $popularPost->published_at->format('d.m.Y') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
