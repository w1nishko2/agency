@extends('layouts.main')

@section('title', 'Регистрация моделью - Golden Models')
@section('description', 'Расширенная регистрация в модельном агентстве Golden Models. Создайте полноценный профиль модели.')

@section('content')

<!-- Hero -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center">
            <h1 class="mb-3">РЕГИСТРАЦИЯ МОДЕЛЬЮ</h1>
            <p class="lead text-muted">Создайте свой профессиональный профиль</p>
        </div>
    </div>
</section>

<!-- Форма регистрации -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ошибки при заполнении формы:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        
                        @auth
                        <div class="alert alert-info border-0 mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Вы авторизованы как {{ $user->name }}</strong><br>
                            <small>Email и телефон будут использованы из вашего аккаунта. Это защитит вас от случайных ошибок при заполнении формы.</small>
                        </div>
                        @endauth

                        <div class="alert alert-warning border-0 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Обратите внимание!</strong><br>
                            <small>Поля, отмеченные <span class="text-danger">*</span>, обязательны для заполнения. Без них модератор не сможет опубликовать вашу анкету.</small>
                        </div>

                        <form action="{{ route('models.register.submit') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                            @csrf

                            <!-- Личные данные -->
                            <h4 class="mb-4">Личные данные</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Имя <span class="text-danger">*</span></label>
                                    @auth
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                                    @else
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    @endauth
                                </div>
                                <div class="col-md-6">
                                    <label for="surname" class="form-label">Фамилия <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Пол <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Выберите...</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Женский</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Мужской</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Дата рождения <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                @auth
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly style="background-color: #e9ecef;">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Email привязан к вашему аккаунту и не может быть изменен
                                    </small>
                                @else
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @endauth
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
                                @auth
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+7 (999) 123-45-67" required>
                                @else
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" required>
                                @endauth
                            </div>

                            <hr class="my-5">

                            <!-- Параметры -->
                            <h4 class="mb-4">Параметры</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="height" class="form-label">Рост (см) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}" min="140" max="220" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="weight" class="form-label">Вес (кг) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" min="40" max="150" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="bust" class="form-label">Грудь (см) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust') }}" min="60" max="150" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="waist" class="form-label">Талия (см) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist') }}" min="50" max="120" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="hips" class="form-label">Бедра (см) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="hips" name="hips" value="{{ old('hips') }}" min="60" max="150" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="shoe_size" class="form-label">Размер обуви <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="shoe_size" name="shoe_size" value="{{ old('shoe_size') }}" step="0.5" min="33" max="46" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="clothing_size" class="form-label">Размер одежды <span class="text-danger">*</span></label>
                                    <select class="form-select" id="clothing_size" name="clothing_size" required>
                                        <option value="">Выберите...</option>
                                        <option value="XS" {{ old('clothing_size') == 'XS' ? 'selected' : '' }}>XS (40-42)</option>
                                        <option value="S" {{ old('clothing_size') == 'S' ? 'selected' : '' }}>S (42-44)</option>
                                        <option value="M" {{ old('clothing_size') == 'M' ? 'selected' : '' }}>M (44-46)</option>
                                        <option value="L" {{ old('clothing_size') == 'L' ? 'selected' : '' }}>L (46-48)</option>
                                        <option value="XL" {{ old('clothing_size') == 'XL' ? 'selected' : '' }}>XL (48-50)</option>
                                        <option value="XXL" {{ old('clothing_size') == 'XXL' ? 'selected' : '' }}>XXL (50-52)</option>
                                        <option value="XXXL" {{ old('clothing_size') == 'XXXL' ? 'selected' : '' }}>XXXL (52+)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="appearance_type" class="form-label">Тип внешности <span class="text-danger">*</span></label>
                                    <select class="form-select" id="appearance_type" name="appearance_type" required>
                                        <option value="">Выберите...</option>
                                        <option value="Славянский" {{ old('appearance_type') == 'Славянский' ? 'selected' : '' }}>Славянский</option>
                                        <option value="Европейский" {{ old('appearance_type') == 'Европейский' ? 'selected' : '' }}>Европейский</option>
                                        <option value="Азиатский" {{ old('appearance_type') == 'Азиатский' ? 'selected' : '' }}>Азиатский</option>
                                        <option value="Афро" {{ old('appearance_type') == 'Афро' ? 'selected' : '' }}>Афро</option>
                                        <option value="Мулат" {{ old('appearance_type') == 'Мулат' ? 'selected' : '' }}>Мулат</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="eye_color" class="form-label">Цвет глаз <span class="text-danger">*</span></label>
                                    <select class="form-select" id="eye_color" name="eye_color" required>
                                        <option value="">Выберите...</option>
                                        <option value="Карие" {{ old('eye_color') == 'Карие' ? 'selected' : '' }}>Карие</option>
                                        <option value="Голубые" {{ old('eye_color') == 'Голубые' ? 'selected' : '' }}>Голубые</option>
                                        <option value="Зеленые" {{ old('eye_color') == 'Зеленые' ? 'selected' : '' }}>Зеленые</option>
                                        <option value="Серые" {{ old('eye_color') == 'Серые' ? 'selected' : '' }}>Серые</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="hair_color" class="form-label">Цвет волос <span class="text-danger">*</span></label>
                                    <select class="form-select" id="hair_color" name="hair_color" required>
                                        <option value="">Выберите...</option>
                                        <option value="Блонд" {{ old('hair_color') == 'Блонд' ? 'selected' : '' }}>Блонд</option>
                                        <option value="Русый" {{ old('hair_color') == 'Русый' ? 'selected' : '' }}>Русый</option>
                                        <option value="Шатен" {{ old('hair_color') == 'Шатен' ? 'selected' : '' }}>Шатен</option>
                                        <option value="Брюнет" {{ old('hair_color') == 'Брюнет' ? 'selected' : '' }}>Брюнет</option>
                                        <option value="Рыжий" {{ old('hair_color') == 'Рыжий' ? 'selected' : '' }}>Рыжий</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Опыт и навыки -->
                            <h4 class="mb-4">Опыт и навыки</h4>

                            <div class="mb-4">
                                <label for="experience" class="form-label">Опыт работы</label>
                                <textarea class="form-control" id="experience" name="experience" rows="4" placeholder="Опишите ваш опыт работы моделью, участие в показах, фотосессиях, работу с брендами...">{{ old('experience') }}</textarea>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Навыки дефиле</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="catwalk_skills" id="catwalk_none" value="none" {{ old('catwalk_skills') == 'none' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="catwalk_none">Нет опыта</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="catwalk_skills" id="catwalk_basic" value="basic" {{ old('catwalk_skills') == 'basic' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="catwalk_basic">Базовые навыки</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="catwalk_skills" id="catwalk_professional" value="professional" {{ old('catwalk_skills') == 'professional' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="catwalk_professional">Профессиональные</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Умение позировать</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="posing_skills" id="posing_none" value="none" {{ old('posing_skills') == 'none' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="posing_none">Нет опыта</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="posing_skills" id="posing_basic" value="basic" {{ old('posing_skills') == 'basic' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="posing_basic">Базовые навыки</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="posing_skills" id="posing_professional" value="professional" {{ old('posing_skills') == 'professional' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="posing_professional">Профессиональные</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Знание иностранных языков</h5>
                            <p class="text-muted small mb-3">Выберите языки, которыми владеете, и укажите уровень</p>

                            <div id="languages-container">
                                <!-- Английский -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[english][enabled]" id="lang_english" value="1" {{ old('languages.english.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_english">Английский</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[english][level]" id="lang_english_level" {{ old('languages.english.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.english.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.english.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.english.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Немецкий -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[german][enabled]" id="lang_german" value="1" {{ old('languages.german.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_german">Немецкий</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[german][level]" id="lang_german_level" {{ old('languages.german.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.german.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.german.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.german.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Французский -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[french][enabled]" id="lang_french" value="1" {{ old('languages.french.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_french">Французский</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[french][level]" id="lang_french_level" {{ old('languages.french.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.french.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.french.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.french.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Испанский -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[spanish][enabled]" id="lang_spanish" value="1" {{ old('languages.spanish.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_spanish">Испанский</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[spanish][level]" id="lang_spanish_level" {{ old('languages.spanish.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.spanish.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.spanish.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.spanish.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Итальянский -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[italian][enabled]" id="lang_italian" value="1" {{ old('languages.italian.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_italian">Итальянский</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[italian][level]" id="lang_italian_level" {{ old('languages.italian.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.italian.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.italian.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.italian.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Китайский -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox" name="languages[chinese][enabled]" id="lang_chinese" value="1" {{ old('languages.chinese.enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="lang_chinese">Китайский</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-select language-level" name="languages[chinese][level]" id="lang_chinese_level" {{ old('languages.chinese.enabled') ? '' : 'disabled' }}>
                                                <option value="">Выберите уровень</option>
                                                <option value="Начальный" {{ old('languages.chinese.level') == 'Начальный' ? 'selected' : '' }}>Начальный</option>
                                                <option value="Разговорный" {{ old('languages.chinese.level') == 'Разговорный' ? 'selected' : '' }}>Разговорный</option>
                                                <option value="Переводчик" {{ old('languages.chinese.level') == 'Переводчик' ? 'selected' : '' }}>Переводчик</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Местоположение -->
                            <h4 class="mb-4">Местоположение</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Город <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Страна</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Россия') }}">
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Социальные сети -->
                            <h4 class="mb-4">Социальные сети</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="vk" class="form-label">VK</label>
                                    <input type="text" class="form-control" id="vk" name="vk" value="{{ old('vk') }}" placeholder="vk.com/username">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="telegram" class="form-label">Telegram</label>
                                    <input type="text" class="form-control" id="telegram" name="telegram" value="{{ old('telegram') }}" placeholder="@username">
                                </div>
                                <div class="col-md-6">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook') }}">
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Фотографии -->
                            <h4 class="mb-4">Фотографии</h4>
                            <p class="text-muted mb-4">Загрузите от 1 до 10 фотографий. Рекомендуем включить портрет, фото в полный рост и профильное фото. Изображения будут автоматически сжаты и конвертированы в формат WebP.</p>

                            <div class="mb-4">
                                <label for="photos" class="form-label">Выберите фото (от 1 до 10 шт) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*" required>
                                <small class="text-muted">Принимаются любые форматы изображений. Размер файла до 10 МБ. Изображения будут автоматически оптимизированы.</small>
                            </div>

                            <div id="photoPreview" class="row g-3 mb-4"></div>

                            <hr class="my-5">

                            <!-- Согласие -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                                    <label class="form-check-label" for="agree">
                                        Я согласен(на) с <a href="#" target="_blank">политикой конфиденциальности</a> и на обработку персональных данных <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary btn-lg">Отправить заявку</button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">Отмена</a>
                            </div>

                            <div class="mt-4">
                                <small class="text-muted"><span class="text-danger">*</span> Поля обязательные для заполнения</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Превью фотографий
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '';
            
            if (this.files.length > 10) {
                alert('Можно загрузить максимум 10 фотографий');
                // Не очищаем, просто предупреждаем
            }

            if (this.files.length === 0) {
                return;
            }
            
            for (let i = 0; i < Math.min(this.files.length, 10); i++) {
                const file = this.files[i];
                
                // Проверка что это изображение (не строгая)
                if (file.type && !file.type.startsWith('image/')) {
                    continue;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'col-6 col-md-4 col-lg-3';
                    div.innerHTML = `
                        <div class="position-relative">
                            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px;">
                            <img src="${e.target.result}" class="img-fluid rounded" style="width: 100%; height: auto; display: block;">
                            </div>
                            <span class="badge bg-dark position-absolute top-0 end-0 m-2">${i === 0 ? 'Главное' : i + 1}</span>
                            <small class="text-muted d-block text-center mt-1">${(file.size / 1024 / 1024).toFixed(2)} МБ</small>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Маска для телефона (не строгая)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formatted = '';
            
            if (value.length > 0) {
                if (value[0] === '8') {
                    value = '7' + value.substring(1);
                }
                if (value[0] === '7') {
                    formatted = '+7';
                    if (value.length > 1) {
                        formatted += ' (' + value.substring(1, 4);
                    }
                    if (value.length >= 5) {
                        formatted += ') ' + value.substring(4, 7);
                    }
                    if (value.length >= 8) {
                        formatted += '-' + value.substring(7, 9);
                    }
                    if (value.length >= 10) {
                        formatted += '-' + value.substring(9, 11);
                    }
                } else {
                    formatted = '+' + value;
                }
                e.target.value = formatted;
            }
        });
    }

    // Обработка чекбоксов языков
    document.querySelectorAll('.language-checkbox').forEach(checkbox => {
        const langName = checkbox.id.replace('lang_', '');
        const levelSelect = document.getElementById(`lang_${langName}_level`);
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                levelSelect.disabled = false;
                levelSelect.required = true;
            } else {
                levelSelect.disabled = true;
                levelSelect.required = false;
                levelSelect.value = '';
            }
        });
    });
</script>
@endpush
