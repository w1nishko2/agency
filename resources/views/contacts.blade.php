@extends('layouts.main')

@section('title', 'Контакты - Golden Models')
@section('description', 'Свяжитесь с модельным агентством Golden Models. Телефон, email, адрес офиса в Москве.')

@section('content')
<div data-page-id="{{ $contactsPage->id ?? '' }}">
<!-- Hero -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo/photo_7_2026-01-24_11-43-44.webp') }}') center/cover;">
    <div class="container text-center">
        <h1 class="mb-3">КОНТАКТЫ</h1>
        <p class="lead text-muted">Свяжитесь с нами удобным способом</p>
    </div>
</section>

<!-- Контакты и форма -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            
            <!-- Контактная информация -->
            <div class="col-lg-5">
                <h3 class="mb-4">Наши контакты</h3>
                
                <div class="mb-4">
                    @if($site_settings['contact']['contact_address'] ?? '')
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Адрес кастингов</h6>
                            <p class="text-muted mb-0">
                                {{ $site_settings['contact']['contact_address'] }}
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if($site_settings['contact']['contact_phone'] ?? '')
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-telephone fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Телефон</h6>
                            <p class="text-muted mb-0">
                                <a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $site_settings['contact']['contact_phone']) }}" class="text-decoration-none text-dark">{{ $site_settings['contact']['contact_phone'] }}</a><br>
                                Иванова Надежда - кастинг директор (по работе с клиентами)
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if($site_settings['contact']['contact_email'] ?? '')
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Email для клиентов</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:{{ $site_settings['contact']['contact_email'] }}" class="text-decoration-none text-dark">{{ $site_settings['contact']['contact_email'] }}</a><br>
                                Иванова Надежда - кастинг директор
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if($site_settings['contact']['contact_email_models'] ?? '')
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Email для моделей</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:{{ $site_settings['contact']['contact_email_models'] }}" class="text-decoration-none text-dark">{{ $site_settings['contact']['contact_email_models'] }}</a><br>
                                Отдел по работе с моделями
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if($site_settings['contact']['contact_phone_partners'] ?? '')
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-telephone fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Сотрудничество и реклама</h6>
                            <p class="text-muted mb-0">
                                <a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $site_settings['contact']['contact_phone_partners']) }}" class="text-decoration-none text-dark">{{ $site_settings['contact']['contact_phone_partners'] }}</a><br>
                                Отдел сотрудничества и рекламы
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mb-4">
                    <h6 class="mb-3">Социальные сети</h6>
                    <div class="social-links">
                        @if($site_settings['social']['social_vk'] ?? '')
                        <a href="{{ $site_settings['social']['social_vk'] }}" target="_blank" class="text-dark">
                            <i class="bi bi-globe"></i>
                        </a>
                        @endif
                        @if($site_settings['social']['social_telegram'] ?? '')
                        <a href="{{ $site_settings['social']['social_telegram'] }}" target="_blank" class="text-dark">
                            <i class="bi bi-telegram"></i>
                        </a>
                        @endif
                        @if($site_settings['social']['social_facebook'] ?? '')
                        <a href="{{ $site_settings['social']['social_facebook'] }}" target="_blank" class="text-dark">
                            <i class="bi bi-facebook"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-light">
                    <h6 class="mb-3">Режим работы</h6>
                    @if($site_settings['contact']['contact_working_hours'] ?? '')
                        <p class="text-muted mb-0">{{ $site_settings['contact']['contact_working_hours'] }}</p>
                    @else
                    <div class="row small">
                        <div class="col-6 mb-2">
                            <strong>Пн-Пт:</strong>
                        </div>
                        <div class="col-6 mb-2 text-end">
                            10:00 - 20:00
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Сб-Вс:</strong>
                        </div>
                        <div class="col-6 mb-2 text-end">
                            11:00 - 18:00
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Форма обратной связи -->
            <div class="col-lg-7">
                <h3 class="mb-4">Напишите нам</h3>
                
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ваше имя *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Телефон *</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Тема обращения</label>
                        <select class="form-select" name="subject">
                            <option value="">Выберите тему</option>
                            <option value="booking">Заказ модели</option>
                            <option value="casting">Вопрос по кастингу</option>
                            <option value="cooperation">Сотрудничество</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Сообщение *</label>
                        <textarea class="form-control" name="message" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">Отправить сообщение</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Карта -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4 text-center">Как нас найти</h3>
        <div class="ratio ratio-21x9">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.5270073899633!2d37.6173!3d55.7558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTXCsDQ1JzIwLjkiTiAzN8KwMzcnMDIuMyJF!5e0!3m2!1sru!2sru!4v1234567890123" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>
</div>
@endsection
