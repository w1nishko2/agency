@extends('layouts.admin')

@section('title', 'Настройки сайта')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Настройки сайта</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gear me-2"></i>Настройки сайта</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Ошибки при сохранении:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Табы для разных групп настроек -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" 
                    id="contact-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#contact" 
                    type="button" 
                    role="tab">
                <i class="bi bi-telephone me-2"></i>Контакты
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" 
                    id="social-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#social" 
                    type="button" 
                    role="tab">
                <i class="bi bi-share me-2"></i>Социальные сети
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" 
                    id="general-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#general" 
                    type="button" 
                    role="tab">
                <i class="bi bi-sliders me-2"></i>Общие настройки
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingsTabContent">
        
        <!-- Контакты -->
        <div class="tab-pane fade show active" 
             id="contact" 
             role="tabpanel" 
             aria-labelledby="contact-tab">
            
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="mb-0">Контактная информация</h5>
                </div>
                <div class="content-card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Телефон -->
                            <div class="mb-4">
                                <label for="contact_phone" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>Телефон (основной)
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_phone" 
                                       name="settings[contact_phone]" 
                                       value="{{ old('settings.contact_phone', $settings['contact']->firstWhere('key', 'contact_phone')->value ?? '') }}"
                                       placeholder="+7 (xxx) xxx-xx-xx">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="contact_email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email (для клиентов)
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="contact_email" 
                                       name="settings[contact_email]" 
                                       value="{{ old('settings.contact_email', $settings['contact']->firstWhere('key', 'contact_email')->value ?? '') }}"
                                       placeholder="info@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Телефон для моделей -->
                            <div class="mb-4">
                                <label for="contact_phone_models" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>Телефон (для моделей)
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_phone_models" 
                                       name="settings[contact_phone_models]" 
                                       value="{{ old('settings.contact_phone_models', $settings['contact']->firstWhere('key', 'contact_phone_models')->value ?? '') }}"
                                       placeholder="+7 (xxx) xxx-xx-xx">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Email для моделей -->
                            <div class="mb-4">
                                <label for="contact_email_models" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Email (для моделей)
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="contact_email_models" 
                                       name="settings[contact_email_models]" 
                                       value="{{ old('settings.contact_email_models', $settings['contact']->firstWhere('key', 'contact_email_models')->value ?? '') }}"
                                       placeholder="casting@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Телефон партнёров -->
                            <div class="mb-4">
                                <label for="contact_phone_partners" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>Телефон (партнёры/реклама)
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_phone_partners" 
                                       name="settings[contact_phone_partners]" 
                                       value="{{ old('settings.contact_phone_partners', $settings['contact']->firstWhere('key', 'contact_phone_partners')->value ?? '') }}"
                                       placeholder="+7 (xxx) xxx-xx-xx">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- WhatsApp -->
                            <div class="mb-4">
                                <label for="contact_whatsapp" class="form-label">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_whatsapp" 
                                       name="settings[contact_whatsapp]" 
                                       value="{{ old('settings.contact_whatsapp', $settings['contact']->firstWhere('key', 'contact_whatsapp')->value ?? '') }}"
                                       placeholder="+7xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Telegram -->
                            <div class="mb-4">
                                <label for="contact_telegram" class="form-label">
                                    <i class="bi bi-telegram me-1"></i>Telegram
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="contact_telegram" 
                                       name="settings[contact_telegram]" 
                                       value="{{ old('settings.contact_telegram', $settings['contact']->firstWhere('key', 'contact_telegram')->value ?? '') }}"
                                       placeholder="@username">
                            </div>
                        </div>
                    </div>

                    <!-- Адрес -->
                    <div class="mb-4">
                        <label for="contact_address" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>Адрес
                        </label>
                        <textarea class="form-control" 
                                  id="contact_address" 
                                  name="settings[contact_address]" 
                                  rows="3"
                                  placeholder="г. Москва, ул. Примерная, д. 1">{{ old('settings.contact_address', $settings['contact']->firstWhere('key', 'contact_address')->value ?? '') }}</textarea>
                    </div>

                    <!-- Время работы -->
                    <div class="mb-4">
                        <label for="contact_working_hours" class="form-label">
                            <i class="bi bi-clock me-1"></i>Часы работы
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="contact_working_hours" 
                               name="settings[contact_working_hours]" 
                               value="{{ old('settings.contact_working_hours', $settings['contact']->firstWhere('key', 'contact_working_hours')->value ?? '') }}"
                               placeholder="Пн-Пт: 9:00-18:00">
                    </div>

                </div>
            </div>

        </div>

        <!-- Социальные сети -->
        <div class="tab-pane fade" 
             id="social" 
             role="tabpanel" 
             aria-labelledby="social-tab">
            
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="mb-0">Ссылки на социальные сети</h5>
                </div>
                <div class="content-card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- VK -->
                            <div class="mb-4">
                                <label for="social_vk" class="form-label">
                                    <i class="bi bi-vk me-1"></i>ВКонтакте
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="social_vk" 
                                       name="settings[social_vk]" 
                                       value="{{ old('settings.social_vk', $settings['social']->firstWhere('key', 'social_vk')->value ?? '') }}"
                                       placeholder="https://vk.com/yourpage">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Instagram -->
                            <div class="mb-4">
                                <label for="social_instagram" class="form-label">
                                    <i class="bi bi-instagram me-1"></i>Instagram
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="social_instagram" 
                                       name="settings[social_instagram]" 
                                       value="{{ old('settings.social_instagram', $settings['social']->firstWhere('key', 'social_instagram')->value ?? '') }}"
                                       placeholder="https://instagram.com/yourpage">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- YouTube -->
                            <div class="mb-4">
                                <label for="social_youtube" class="form-label">
                                    <i class="bi bi-youtube me-1"></i>YouTube
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="social_youtube" 
                                       name="settings[social_youtube]" 
                                       value="{{ old('settings.social_youtube', $settings['social']->firstWhere('key', 'social_youtube')->value ?? '') }}"
                                       placeholder="https://youtube.com/channel">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Facebook -->
                            <div class="mb-4">
                                <label for="social_facebook" class="form-label">
                                    <i class="bi bi-facebook me-1"></i>Facebook
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="social_facebook" 
                                       name="settings[social_facebook]" 
                                       value="{{ old('settings.social_facebook', $settings['social']->firstWhere('key', 'social_facebook')->value ?? '') }}"
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Telegram -->
                            <div class="mb-4">
                                <label for="social_telegram" class="form-label">
                                    <i class="bi bi-telegram me-1"></i>Telegram канал
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="social_telegram" 
                                       name="settings[social_telegram]" 
                                       value="{{ old('settings.social_telegram', $settings['social']->firstWhere('key', 'social_telegram')->value ?? '') }}"
                                       placeholder="https://t.me/yourchannel">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Общие настройки -->
        <div class="tab-pane fade" 
             id="general" 
             role="tabpanel" 
             aria-labelledby="general-tab">
            
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="mb-0">Общие настройки сайта</h5>
                </div>
                <div class="content-card-body">
                    
                    <!-- Название сайта -->
                    <div class="mb-4">
                        <label for="site_name" class="form-label">
                            <i class="bi bi-globe me-1"></i>Название сайта
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="site_name" 
                               name="settings[site_name]" 
                               value="{{ old('settings.site_name', $settings['general']->firstWhere('key', 'site_name')->value ?? '') }}"
                               placeholder="Модельное агентство">
                    </div>

                    <!-- Описание сайта -->
                    <div class="mb-4">
                        <label for="site_description" class="form-label">
                            <i class="bi bi-text-paragraph me-1"></i>Описание сайта
                        </label>
                        <textarea class="form-control" 
                                  id="site_description" 
                                  name="settings[site_description]" 
                                  rows="3"
                                  placeholder="Краткое описание для SEO">{{ old('settings.site_description', $settings['general']->firstWhere('key', 'site_description')->value ?? '') }}</textarea>
                    </div>

                    <!-- Ключевые слова -->
                    <div class="mb-4">
                        <label for="site_keywords" class="form-label">
                            <i class="bi bi-tags me-1"></i>Ключевые слова
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="site_keywords" 
                               name="settings[site_keywords]" 
                               value="{{ old('settings.site_keywords', $settings['general']->firstWhere('key', 'site_keywords')->value ?? '') }}"
                               placeholder="модели, агентство, фотосессии">
                        <small class="text-muted">Через запятую</small>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- Кнопка сохранения -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save me-2"></i>Сохранить все настройки
        </button>
    </div>

</form>

@endsection
