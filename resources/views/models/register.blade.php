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
                        <form action="{{ route('models.register.submit') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                            @csrf

                            <!-- Личные данные -->
                            <h4 class="mb-4">Личные данные</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Имя</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="surname" class="form-label">Фамилия</label>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Пол</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Выберите...</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Женский</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Мужской</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Дата рождения</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67">
                            </div>

                            <hr class="my-5">

                            <!-- Параметры -->
                            <h4 class="mb-4">Параметры</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="height" class="form-label">Рост (см)</label>
                                    <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="weight" class="form-label">Вес (кг)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="bust" class="form-label">Грудь (см)</label>
                                    <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="waist" class="form-label">Талия (см)</label>
                                    <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist') }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="hips" class="form-label">Бедра (см)</label>
                                    <input type="number" class="form-control" id="hips" name="hips" value="{{ old('hips') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="shoe_size" class="form-label">Размер обуви</label>
                                    <input type="number" class="form-control" id="shoe_size" name="shoe_size" value="{{ old('shoe_size') }}" step="0.5">
                                </div>
                                <div class="col-md-3">
                                    <label for="eye_color" class="form-label">Цвет глаз</label>
                                    <select class="form-select" id="eye_color" name="eye_color">
                                        <option value="">Выберите...</option>
                                        <option value="Карие" {{ old('eye_color') == 'Карие' ? 'selected' : '' }}>Карие</option>
                                        <option value="Голубые" {{ old('eye_color') == 'Голубые' ? 'selected' : '' }}>Голубые</option>
                                        <option value="Зеленые" {{ old('eye_color') == 'Зеленые' ? 'selected' : '' }}>Зеленые</option>
                                        <option value="Серые" {{ old('eye_color') == 'Серые' ? 'selected' : '' }}>Серые</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="hair_color" class="form-label">Цвет волос</label>
                                    <select class="form-select" id="hair_color" name="hair_color">
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

                            <hr class="my-5">

                            <!-- Местоположение -->
                            <h4 class="mb-4">Местоположение</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Город</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
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
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="@username">
                                </div>
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
                                <label for="photos" class="form-label">Выберите фото (от 1 до 10 шт)</label>
                                <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                                <small class="text-muted">Принимаются любые форматы изображений. Размер не ограничен - изображения будут автоматически оптимизированы.</small>
                            </div>

                            <div id="photoPreview" class="row g-3 mb-4"></div>

                            <hr class="my-5">

                            <!-- Согласие -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree" name="agree">
                                    <label class="form-check-label" for="agree">
                                        Я согласен(на) с <a href="#" target="_blank">политикой конфиденциальности</a> и на обработку персональных данных
                                    </label>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary btn-lg">Отправить заявку</button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">Отмена</a>
                            </div>

                            <div class="mt-4">
                                <small class="text-muted">Все поля не обязательны для заполнения</small>
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
                            <img src="${e.target.result}" class="img-fluid rounded" style="aspect-ratio: 3/4; object-fit: cover;">
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
</script>
@endpush
