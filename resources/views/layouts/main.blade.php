<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Golden Models - Модельное агентство')</title>

    <!-- SEO -->
    <meta name="description" content="@yield('description', 'Модельное агентство Golden Models - кастинг, подбор и продвижение моделей по всей России')">
    <meta name="keywords" content="@yield('keywords', 'модельное агентство, кастинг моделей, подбор моделей, Golden Models')">
    
    <!-- OG теги -->
    <meta property="og:title" content="@yield('og_title', 'Golden Models - Модельное агентство')">
    <meta property="og:description" content="@yield('og_description', 'Кастинг, подбор и продвижение моделей')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:type" content="website">
    
    @stack('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Stub Functions CSS (Этап 2) -->
    <link rel="stylesheet" href="{{ asset('css/stub-functions.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                GOLDEN MODELS
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Меню">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/models') }}">Модели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/casting') }}">Кастинг</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about') }}">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/blog') }}">Блог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contacts') }}">Контакты</a>
                    </li>
                    
                    @auth
                        <!-- Профиль пользователя -->
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Мой профиль</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Выйти</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Вход/Регистрация -->
                        <li class="nav-item ms-3">
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Войти</a>
                        </li>
                    @endauth
                    
                    <!-- Социальные сети для десктопа -->
                    <li class="nav-item d-none d-lg-block ms-3">
                        <div class="social-links">
                            <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://vk.com" target="_blank" aria-label="VK">
                                <i class="bi bi-globe"></i>
                            </a>
                            <a href="https://t.me" target="_blank" aria-label="Telegram">
                                <i class="bi bi-telegram"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main style="padding-top: 76px;">
        @yield('content')
    </main>

    <!-- Футер -->
    <footer>
        <div class="container">
            <div class="row footer-main">
                <div class="col-lg-4 mb-4 mb-lg-0 footer-brand">
                    <h5 class="text-uppercase mb-3">Golden Models</h5>
                    <p class="text-muted small">Модельное агентство полного цикла. Кастинг, подбор и продвижение моделей по всей России.</p>
                </div>
                
                <div class="col-lg-2 col-6 mb-4 mb-lg-0 footer-links">
                    <h6 class="text-uppercase mb-3">Навигация</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-muted text-decoration-none">Главная</a></li>
                        <li class="mb-2"><a href="{{ url('/models') }}" class="text-muted text-decoration-none">Модели</a></li>
                        <li class="mb-2"><a href="{{ url('/casting') }}" class="text-muted text-decoration-none">Кастинг</a></li>
                        <li class="mb-2"><a href="{{ url('/about') }}" class="text-muted text-decoration-none">О нас</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-6 mb-4 mb-lg-0 footer-links">
                    <h6 class="text-uppercase mb-3">Информация</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/blog') }}" class="text-muted text-decoration-none">Блог</a></li>
                        <li class="mb-2"><a href="{{ url('/contacts') }}" class="text-muted text-decoration-none">Контакты</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 footer-contact">
                    <h6 class="text-uppercase mb-3">Контакты</h6>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-telephone me-2"></i>+7 (999) 123-45-67
                    </p>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-envelope me-2"></i>info@golden-models.ru
                    </p>
                    
                    <div class="social-links">
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://vk.com" target="_blank" aria-label="VK">
                            <i class="bi bi-globe"></i>
                        </a>
                        <a href="https://t.me" target="_blank" aria-label="Telegram">
                            <i class="bi bi-telegram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row footer-bottom">
                <div class="col-12 text-center">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} Golden Models. Все права защищены.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Stub Functions Script (Этап 2) -->
    <script src="{{ asset('js/stub-functions.js') }}"></script>
    
    <!-- Demo Badge (можно убрать при желании) -->
    <div class="stub-demo-badge">
        <i class="bi bi-tools"></i>
        Демо-версия (Этап 2)
    </div>

    @stack('scripts')
</body>
</html>
