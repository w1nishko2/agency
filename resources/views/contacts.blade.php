@extends('layouts.main')

@section('title', 'Контакты - Golden Models')
@section('description', 'Свяжитесь с модельным агентством Golden Models. Телефон, email, адрес офиса в Москве.')

@section('content')

<!-- Hero -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo_7_2025-11-27_12-56-07.webp') }}') center/cover;">
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
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Адрес</h6>
                            <p class="text-muted mb-0">
                                Москва, ул. Примерная, д. 1, офис 101<br>
                                Метро "Примерная"
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-telephone fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Телефон</h6>
                            <p class="text-muted mb-0">
                                <a href="tel:+79991234567" class="text-decoration-none text-dark">+7 (999) 123-45-67</a><br>
                                Ежедневно с 10:00 до 20:00
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:info@golden-models.ru" class="text-decoration-none text-dark">info@golden-models.ru</a><br>
                                Ответим в течение 24 часов
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-telegram fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">Telegram</h6>
                            <p class="text-muted mb-0">
                                <a href="https://t.me/goldenmodels" class="text-decoration-none text-dark">@goldenmodels</a><br>
                                Быстрый ответ в мессенджере
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="mb-3">Социальные сети</h6>
                    <div class="social-links">
                        <a href="https://vk.com" target="_blank" class="text-dark">
                            <i class="bi bi-globe"></i>
                        </a>
                        <a href="https://t.me" target="_blank" class="text-dark">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="https://facebook.com" target="_blank" class="text-dark">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>

                <div class="p-4 bg-light">
                    <h6 class="mb-3">Режим работы</h6>
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

@endsection
