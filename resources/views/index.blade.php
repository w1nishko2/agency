@extends('layouts.main')

@section('title', 'Golden Models - Модельное агентство')
@section('description', 'Модельное агентство Golden Models - кастинг, подбор и продвижение моделей по всей России. 10 лет опыта, более 3000 моделей.')

@section('content')

@if($homePage)
<div data-page-id="{{ $homePage->id }}">
@endif

<!-- Hero Section -->
@php
    $heroImage = 'imgsite/photo/photo_1_2026-01-24_11-43-44.webp';
    if ($homePage && $homePage->image) {
        $heroImageUrl = asset('storage/' . $homePage->image);
    } else {
        $heroImageUrl = asset($heroImage);
    }
@endphp
<section class="hero-section" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $heroImageUrl }}') center/cover;">
    <div class="hero-overlay"></div>
    <div class="hero-content container">
        <h1 class="hero-title animate__animated animate__fadeInDown">GOLDEN MODELS</h1>
        <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">Профессиональное модельное агентство</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap animate__animated animate__fadeInUp animate__delay-2s">
            <a href="{{ url('/models') }}" class="btn btn-lg" style="background-color: rgba(0, 0, 0, 0.3); color: white; backdrop-filter: blur(5px);">Каталог моделей</a>
            <a href="{{ route('models.register') }}" class="btn btn-outline-light btn-lg" style="background-color: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.6); backdrop-filter: blur(5px);">Стать моделью</a>
        </div>
    </div>
</section>

<!-- О нас -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="position-relative rounded shadow overflow-hidden" style="height: 500px;">
                    @php
                        // Получаем изображения из базы или используем дефолтные
                        $aboutImages = [];
                        if ($homePage && $homePage->images) {
                            for ($i = 1; $i <= 4; $i++) {
                                $key = "about_image_{$i}";
                                if (isset($homePage->images[$key])) {
                                    $aboutImages[] = asset('storage/' . $homePage->images[$key]);
                                }
                            }
                        }
                        
                        // Если нет изображений в базе, используем дефолтные
                        if (empty($aboutImages)) {
                            $aboutImages = [
                                asset('imgsite/photo/photo_2_2026-01-24_11-43-44.webp'),
                                asset('imgsite/photo/photo_3_2026-01-24_11-43-44.webp'),
                                asset('imgsite/photo/photo_4_2026-01-24_11-43-44.webp'),
                                asset('imgsite/photo/photo_6_2026-01-24_11-43-44.webp'),
                            ];
                        }
                    @endphp
                    @foreach($aboutImages as $index => $imageUrl)
                    <img src="{{ $imageUrl }}" 
                         alt="Golden Models {{ $index + 1 }}" 
                         class="carousel-image position-absolute w-100 h-100"
                         style="object-fit: cover; opacity: {{ $index === 0 ? 1 : 0 }}; transition: opacity 1s ease-in-out;"
                         data-index="{{ $index }}">
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-start">
                <span class="text-uppercase text-muted small d-block mb-2">О нас</span>
                <h2 class="mb-4">Профессиональное модельное агентство</h2>
                <p class="text-muted mb-4">
                    Golden Models — это модельное агентство полного цикла с многолетним опытом работы. 
                    Мы специализируемся на поиске, обучении и продвижении моделей.
                </p>
                <a href="{{ url('/about') }}" class="btn btn-outline-dark">Подробнее о нас</a>
            </div>
        </div>
    </div>
</section>

<!-- Статистика -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            @php
                $stats = [
                    'models' => \App\Models\ModelProfile::where('status', 'active')->count(),
                    'posts' => \App\Models\BlogPost::published()->count(),
                ];
            @endphp
            
            @if($stats['models'] > 0)
            <div class="col-md-6">
                <div class="p-4">
                    <div class="display-4 mb-3">{{ $stats['models'] }}</div>
                    <h5 class="text-uppercase small mb-2">Активных моделей</h5>
                </div>
            </div>
            @endif
            
            @if($stats['posts'] > 0)
            <div class="col-md-6">
                <div class="p-4">
                    <div class="display-4 mb-3">{{ $stats['posts'] }}</div>
                    <h5 class="text-uppercase small mb-2">Статей в блоге</h5>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Наши модели -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Портфолио</span>
            <h2>Наши модели</h2>
        </div>
        
        @php
            $featuredModels = \App\Models\ModelProfile::active()
                ->where('is_featured', true)
                ->limit(8)
                ->get();
        @endphp
        
        @if($featuredModels->count() > 0)
        <div class="row g-4">
            @foreach($featuredModels as $model)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('models.show', $model->id) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100">
                        <div style="background: #f8f9fa;">
                        @if($model->photos && count($model->photos) > 0)
                            <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                                 alt="{{ $model->full_name }}" 
                                 class="card-img-top"
                                 style="width: 100%; aspect-ratio: 2/3; object-fit: cover;"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('imgsite/placeholder.svg') }}" 
                                 alt="Фото отсутствует" 
                                 class="card-img-top"
                                 style="width: 100%; height: 300px; object-fit: contain;"
                                 loading="lazy">
                        @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $model->full_name }}</h5>
                            <p class="text-muted small mb-2">
                                {{ $model->city }} • {{ $model->height }} см
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <p class="text-muted">Модели скоро появятся</p>
        </div>
        @endif
        
        <div class="text-center mt-5">
            <a href="{{ url('/models') }}" class="btn btn-outline-dark">Смотреть все модели</a>
        </div>
    </div>
</section>

<!-- Блог / Полезное -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Полезное</span>
            <h2>Последние статьи</h2>
        </div>
        
        @if(isset($latestPosts) && $latestPosts->count() > 0)
            <div class="row g-4">
                @foreach($latestPosts as $post)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <img src="{{ asset('imgsite/photo_5_2025-11-27_12-56-07.webp') }}" 
                                     class="card-img-top" 
                                     alt="Блог"
                                     loading="lazy"
                                     style="height: 250px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted mb-2">{{ $post->published_at->format('d.m.Y') }}</small>
                                <h5 class="card-title">{{ Str::limit($post->title, 60) }}</h5>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}
                                </p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-dark mt-auto">
                                    Читать далее
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('blog.index') }}" class="btn btn-outline-dark">Все статьи</a>
            </div>
        @else
            <div class="text-center text-muted">
                <p>Статьи скоро появятся</p>
            </div>
        @endif
    </div>
</section>

<!-- Партнёры -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Партнёры</span>
            <h2>Нам доверяют</h2>
        </div>
        
        <div class="partners-scroll-wrapper">
            <div class="partners-scroll">
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">FASHION HOUSE</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">BEAUTY BRAND</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">MAGAZINE</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">STYLE STUDIO</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">AD AGENCY</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">LUXURY BRAND</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">EVENT COMPANY</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">MEDIA GROUP</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ищете модель для проекта?</h2>
        <p class="lead mb-4">Заполните заявку и мы подберем идеальную модель по вашим критериям</p>
        <a href="{{ route('casting.index') }}" class="btn btn-outline-light btn-lg">Подобрать модель</a>
    </div>
</section>

<!-- Контакты -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Контакты</span>
            <h2>Свяжитесь с нами</h2>
        </div>
        
        <div class="row g-4">
            <!-- Форма -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <form action="{{ route('contact.submit') }}" method="POST" class="p-4 bg-light">
                    @csrf
                    <h5 class="mb-4">Написать нам</h5>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Ваше имя *" 
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <input type="tel" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="Телефон *" 
                               value="{{ old('phone') }}"
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <textarea name="message" 
                                  class="form-control @error('message') is-invalid @enderror" 
                                  rows="5" 
                                  placeholder="Сообщение *" 
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                        <a href="https://t.me/goldenmodels" 
                           target="_blank" 
                           class="btn btn-outline-primary">
                            <i class="bi bi-telegram me-2"></i>Написать в Telegram
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Контактная информация и карта -->
            <div class="col-lg-6">
                <div class="mb-4 p-4 bg-light">
                    <h5 class="mb-4">Наши контакты</h5>
                    
                    <div class="mb-3">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span>Москва, м. Цветной Бульвар, ул. Цветной Бульвар, д.19 строение 5, 4 этаж</span>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:+79057173012" class="text-decoration-none text-dark">+7 905 717 30 12</a>
                        <br><small class="text-muted">Иванова Надежда - кастинг директор</small>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:gma@golden-models.ru" class="text-decoration-none text-dark">gma@golden-models.ru</a>
                        <br><small class="text-muted">Для клиентов</small>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:casting@golden-models.ru" class="text-decoration-none text-dark">casting@golden-models.ru</a>
                        <br><small class="text-muted">Для моделей</small>
                    </div>
                    
                    <div class="mb-4">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:+79067299717" class="text-decoration-none text-dark">+7 906 729 97 17</a>
                        <br><small class="text-muted">Сотрудничество и реклама</small>
                    </div>
                    
                    <div class="social-links d-flex gap-3">
                        <a href="https://vk.com/goldenmodels" target="_blank" class="text-dark fs-4">
                            <i class="bi bi-globe"></i>
                        </a>
                        <a href="https://t.me/goldenmodels" target="_blank" class="text-dark fs-4">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="https://facebook.com/goldenmodels" target="_blank" class="text-dark fs-4">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Яндекс Карта -->
                <div id="map" style="width: 100%; height: 300px;" class="mb-4"></div>
            </div>
        </div>
    </div>
</section>

@if($homePage)
</div>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
.btn-outline-light {
    border-color: #FFFFFF;
    color: #FFFFFF;
}
.btn-outline-light:hover {
    background-color: #FFFFFF;
    color: #000000;
}

.testimonial-card {
    border-radius: 8px;
}

.partner-logo {
    border-radius: 8px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.partner-logo:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1);
}

#categoriesCarousel .carousel-control-prev-icon,
#categoriesCarousel .carousel-control-next-icon {
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    padding: 20px;
}
</style>
@endpush

@push('scripts')
<!-- Яндекс Карты API -->
<script src="https://api-maps.yandex.ru/2.1/?apikey=YOUR_API_KEY&lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
    // Инициализация карты
    ymaps.ready(init);
    
    function init() {
        var myMap = new ymaps.Map("map", {
            center: [55.771899, 37.620393], // Цветной Бульвар, д.19 стр.5
            zoom: 16,
            controls: ['zoomControl', 'fullscreenControl']
        });

        var myPlacemark = new ymaps.Placemark([55.771899, 37.620393], {
            balloonContent: '<strong>Golden Models</strong><br>Москва, м. Цветной Бульвар<br>ул. Цветной Бульвар, д.19 строение 5, 4 этаж<br><a href="tel:+79057173012">+7 905 717 30 12</a>'
        }, {
            preset: 'islands#icon',
            iconColor: '#0d6efd'
        });

        myMap.geoObjects.add(myPlacemark);
    }
    
    // Автоматическая прокрутка партнеров
    document.addEventListener('DOMContentLoaded', function() {
        const partnersScroll = document.querySelector('.partners-scroll');
        if (partnersScroll) {
            let scrollAmount = 0;
            const scrollSpeed = 1; // пикселей за шаг
            const scrollDelay = 30; // миллисекунд между шагами
            
            function autoScroll() {
                scrollAmount += scrollSpeed;
                
                // Если достигли конца, возвращаемся в начало
                if (scrollAmount >= partnersScroll.scrollWidth - partnersScroll.clientWidth) {
                    scrollAmount = 0;
                }
                
                partnersScroll.scrollLeft = scrollAmount;
            }
            
            // Запускаем автопрокрутку
            const scrollInterval = setInterval(autoScroll, scrollDelay);
            
            // Останавливаем при наведении мыши
            partnersScroll.addEventListener('mouseenter', function() {
                clearInterval(scrollInterval);
            });
            
            // Возобновляем при уходе мыши (опционально)
            // partnersScroll.addEventListener('mouseleave', function() {
            //     scrollInterval = setInterval(autoScroll, scrollDelay);
            // });
        }

        // Карусель фотографий в секции "О нас"
        const carouselImages = document.querySelectorAll('.carousel-image');
        if (carouselImages.length > 0) {
            let currentIndex = 0;
            
            function changeImage() {
                // Скрываем текущее изображение
                carouselImages[currentIndex].style.opacity = '0';
                
                // Переходим к следующему изображению
                currentIndex = (currentIndex + 1) % carouselImages.length;
                
                // Показываем следующее изображение
                carouselImages[currentIndex].style.opacity = '1';
            }
            
            // Меняем изображение каждые 3 секунды
            setInterval(changeImage, 3000);
        }
    });
</script>
@endpush
