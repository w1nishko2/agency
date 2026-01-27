@extends('layouts.main')

@section('title', 'О нас - Golden Models')
@section('description', 'Golden Models - профессиональное модельное агентство с 10-летним опытом работы. Более 3000 моделей, работа по всей России.')

@section('content')

<!-- Hero -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo/photo_2_2026-01-24_11-43-44.webp') }}') center/cover;">
    <div class="container text-center">
        <h1 class="mb-3">О НАС</h1>
        <p class="lead text-muted">Профессиональное модельное агентство полного цикла</p>
    </div>
</section>

<!-- История -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0 order-lg-2">
                <img src="{{ asset('imgsite/photo/photo_3_2026-01-24_11-43-44.webp') }}" 
                     alt="Golden Models История" 
                     class="img-fluid rounded shadow"
                     style="width: 100%; height: 500px; object-fit: cover;">
            </div>
            <div class="col-lg-6 mb-4 mb-lg-0 order-lg-1">
                <span class="text-uppercase text-muted small d-block mb-2">Наша история</span>
                <h2 class="mb-4">Golden Models с 2014 года</h2>
                <p class="text-muted mb-3">
                    Golden Models — это профессиональное модельное агентство полного цикла, основанное в 2014 году. 
                    За более чем 10 лет работы мы стали одним из ведущих агентств России, представляя интересы 
                    более 3000 моделей различных категорий.
                </p>
                <p class="text-muted mb-3">
                    Мы специализируемся на поиске, обучении и продвижении моделей как на российском, 
                    так и на международном рынке. Наши модели работают с ведущими мировыми брендами, 
                    участвуют в престижных показах и рекламных кампаниях.
                </p>
                <p class="text-muted">
                    Наша миссия — открывать новые таланты и создавать условия для их профессионального роста. 
                    Мы верим в индивидуальность каждой модели и помогаем раскрыть её потенциал.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Статистика -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="display-3 mb-2">10+</div>
                <div class="text-uppercase small">Лет на рынке</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-3 mb-2">3000+</div>
                <div class="text-uppercase small">Моделей в базе</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-3 mb-2">500+</div>
                <div class="text-uppercase small">Проектов в год</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-3 mb-2">50+</div>
                <div class="text-uppercase small">Городов России</div>
            </div>
        </div>
    </div>
</section>

<!-- Услуги -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Наши услуги</span>
            <h2>Что мы предлагаем</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-person-plus display-5"></i>
                    </div>
                    <h4 class="mb-3">Подбор моделей</h4>
                    <p class="text-muted mb-0">
                        Индивидуальный подбор моделей под любой проект: реклама, каталоги, показы, 
                        промо-акции. Гарантируем профессионализм и соответствие требованиям.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-mortarboard display-5"></i>
                    </div>
                    <h4 class="mb-3">Обучение моделей</h4>
                    <p class="text-muted mb-0">
                        Комплексная подготовка начинающих моделей: дефиле, позирование, актерское мастерство. 
                        Опытные преподаватели и современные методики обучения.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-globe display-5"></i>
                    </div>
                    <h4 class="mb-3">Международное продвижение</h4>
                    <p class="text-muted mb-0">
                        Помощь в выходе на международный рынок. Партнерство с ведущими зарубежными 
                        агентствами и организация работы за рубежом.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-camera display-5"></i>
                    </div>
                    <h4 class="mb-3">Фотосессии</h4>
                    <p class="text-muted mb-0">
                        Создание профессионального портфолио: съемка в студии и на локации, 
                        обработка фото, подготовка портфолио для кастингов.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-people display-5"></i>
                    </div>
                    <h4 class="mb-3">Кастинги</h4>
                    <p class="text-muted mb-0">
                        Организация и проведение кастингов для брендов и рекламных агентств. 
                        Профессиональный отбор моделей под требования проекта.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-headset display-5"></i>
                    </div>
                    <h4 class="mb-3">Поддержка 24/7</h4>
                    <p class="text-muted mb-0">
                        Круглосуточная поддержка клиентов и моделей. Оперативное решение любых 
                        вопросов, связанных с организацией съемок и мероприятий.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Партнеры -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small d-block mb-2">Партнеры</span>
            <h2>С нами работают</h2>
        </div>
        
        <div class="partners-scroll-wrapper">
            <div class="partners-scroll">
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">BRAND ONE</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">FASHION CO</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">STYLE GROUP</h4>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">ELITE MEDIA</h4>
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
                <div class="partner-item">
                    <div class="partner-logo p-4 bg-white text-center">
                        <h4 class="text-muted mb-0">AD AGENCY</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const partnersScroll = document.querySelector('.partners-scroll');
        if (partnersScroll) {
            let scrollAmount = 0;
            const scrollSpeed = 1;
            const scrollDelay = 30;
            
            function autoScroll() {
                scrollAmount += scrollSpeed;
                partnersScroll.scrollLeft = scrollAmount;
                
                if (scrollAmount >= partnersScroll.scrollWidth - partnersScroll.clientWidth) {
                    scrollAmount = 0;
                }
            }
            
            setInterval(autoScroll, scrollDelay);
            
            partnersScroll.addEventListener('mouseenter', function() {
                clearInterval(autoScroll);
            });
        }
    });
</script>

<!-- CTA -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-4">Готовы работать с нами?</h2>
        <p class="lead text-muted mb-4">Свяжитесь с нами для обсуждения вашего проекта</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ url('/contacts') }}" class="btn btn-primary">Связаться</a>
            <a href="{{ url('/casting') }}" class="btn btn-outline-dark">Стать моделью</a>
        </div>
    </div>
</section>

@endsection
