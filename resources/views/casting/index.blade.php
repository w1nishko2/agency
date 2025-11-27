@extends('layouts.main')

@section('title', 'Кастинг моделей - Golden Models')
@section('description', 'Станьте моделью агентства Golden Models. Пройдите онлайн-кастинг и начните карьеру в модельном бизнесе.')

@section('content')

<!-- Hero блок -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo_6_2025-11-27_12-56-07.webp') }}') center/cover;">
    <div class="container text-center">
        <h1 class="mb-3">КАСТИНГ МОДЕЛЕЙ</h1>
        <p class="lead text-muted">Заполните анкету и станьте частью нашего агентства</p>
    </div>
</section>

<!-- Форма кастинга -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Прогресс-бар -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Шаг <span id="current-step">1</span> из 12</small>
                        <small class="text-muted"><span id="progress-percent">8</span>%</small>
                    </div>
                    <div class="progress" style="height: 3px;">
                        <div id="progress-bar" class="progress-bar bg-dark" role="progressbar" style="width: 8%"></div>
                    </div>
                </div>

                <form id="casting-form" action="{{ route('casting.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Шаг 1: Пол -->
                    <div class="form-step active" data-step="1">
                        <h3 class="mb-4">Выберите пол</h3>
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="gender" id="gender-female" value="female" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="gender-female">
                                    <i class="bi bi-gender-female d-block mb-2" style="font-size: 2rem;"></i>
                                    Женский
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="gender" id="gender-male" value="male" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="gender-male">
                                    <i class="bi bi-gender-male d-block mb-2" style="font-size: 2rem;"></i>
                                    Мужской
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 2: Возраст -->
                    <div class="form-step" data-step="2">
                        <h3 class="mb-4">Укажите возраст</h3>
                        <select class="form-select form-select-lg" name="age" required>
                            <option value="">Выберите возрастную категорию</option>
                            <option value="0-3">0-3 года</option>
                            <option value="4-7">4-7 лет</option>
                            <option value="8-12">8-12 лет</option>
                            <option value="13-17">13-17 лет</option>
                            <option value="18-25">18-25 лет</option>
                            <option value="26-35">26-35 лет</option>
                            <option value="36-45">36-45 лет</option>
                            <option value="46+">46+ лет</option>
                        </select>
                    </div>

                    <!-- Шаг 3: Цвет глаз -->
                    <div class="form-step" data-step="3">
                        <h3 class="mb-4">Цвет глаз</h3>
                        <select class="form-select form-select-lg" name="eye_color" required>
                            <option value="">Выберите цвет глаз</option>
                            <option value="Карие">Карие</option>
                            <option value="Голубые">Голубые</option>
                            <option value="Зелёные">Зелёные</option>
                            <option value="Серые">Серые</option>
                            <option value="Чёрные">Чёрные</option>
                        </select>
                    </div>

                    <!-- Шаг 4: Цвет волос -->
                    <div class="form-step" data-step="4">
                        <h3 class="mb-4">Цвет волос</h3>
                        <select class="form-select form-select-lg" name="hair_color" required>
                            <option value="">Выберите цвет волос</option>
                            <option value="Блонд">Блонд</option>
                            <option value="Русый">Русый</option>
                            <option value="Каштановый">Каштановый</option>
                            <option value="Рыжий">Рыжий</option>
                            <option value="Чёрный">Чёрный</option>
                            <option value="Седой">Седой</option>
                        </select>
                    </div>

                    <!-- Шаг 5: Рост -->
                    <div class="form-step" data-step="5">
                        <h3 class="mb-4">Рост (см)</h3>
                        <input type="number" class="form-control form-control-lg" name="height" 
                               placeholder="Например: 175" min="50" max="250" required>
                    </div>

                    <!-- Шаг 6: Вес -->
                    <div class="form-step" data-step="6">
                        <h3 class="mb-4">Вес (кг)</h3>
                        <input type="number" class="form-control form-control-lg" name="weight" 
                               placeholder="Например: 60" min="20" max="200" required>
                    </div>

                    <!-- Шаг 7: Обучение -->
                    <div class="form-step" data-step="7">
                        <h3 class="mb-4">Обучались ли в школе моделей?</h3>
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="model_school" id="school-yes" value="yes" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="school-yes">Да</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="model_school" id="school-no" value="no" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="school-no">Нет</label>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 8: Дефиле -->
                    <div class="form-step" data-step="8">
                        <h3 class="mb-4">Навыки дефиле</h3>
                        <select class="form-select form-select-lg" name="catwalk_skills" required>
                            <option value="">Выберите уровень</option>
                            <option value="Нет опыта">Нет опыта</option>
                            <option value="Начальный">Начальный</option>
                            <option value="Средний">Средний</option>
                            <option value="Профессиональный">Профессиональный</option>
                        </select>
                    </div>

                    <!-- Шаг 9: Позирование -->
                    <div class="form-step" data-step="9">
                        <h3 class="mb-4">Умение позировать</h3>
                        <select class="form-select form-select-lg" name="posing_skills" required>
                            <option value="">Выберите уровень</option>
                            <option value="Нет опыта">Нет опыта</option>
                            <option value="Начальный">Начальный</option>
                            <option value="Средний">Средний</option>
                            <option value="Профессиональный">Профессиональный</option>
                        </select>
                    </div>

                    <!-- Шаг 10: Гражданство -->
                    <div class="form-step" data-step="10">
                        <h3 class="mb-4">Гражданство</h3>
                        <input type="text" class="form-control form-control-lg" name="citizenship" 
                               placeholder="Например: РФ" required>
                    </div>

                    <!-- Шаг 11: Регион -->
                    <div class="form-step" data-step="11">
                        <h3 class="mb-4">Регион проживания</h3>
                        <input type="text" class="form-control form-control-lg" name="region" 
                               placeholder="Например: Москва" required>
                    </div>

                    <!-- Шаг 12: Фото -->
                    <div class="form-step" data-step="12">
                        <h3 class="mb-4">Загрузите фотографии</h3>
                        <p class="text-muted mb-4">Загрузите от 3 до 5 фотографий (портрет, в полный рост, профиль)</p>
                        
                        <div class="mb-4">
                            <input type="file" class="form-control" name="photos[]" 
                                   accept="image/*" multiple required id="photo-input">
                            <small class="text-muted">Любые форматы изображений. Мы автоматически сжимаем и оптимизируем фотографии</small>
                        </div>

                        <div id="photo-preview" class="row g-3 mb-4"></div>

                        <h4 class="mb-3">Контактная информация</h4>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" 
                                   placeholder="Ваше имя" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" name="phone" 
                                   placeholder="Телефон" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" 
                                   placeholder="Email (необязательно)">
                        </div>
                    </div>

                    <!-- Кнопки навигации -->
                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-dark" id="prev-btn" style="display: none;">
                            <i class="bi bi-arrow-left me-2"></i>Назад
                        </button>
                        <button type="button" class="btn btn-primary ms-auto" id="next-btn">
                            Далее<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary ms-auto" id="submit-btn" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="submit-spinner"></span>
                            <span id="submit-text">Отправить анкету</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('casting-form');
    const steps = document.querySelectorAll('.form-step');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const progressBar = document.getElementById('progress-bar');
    const currentStepSpan = document.getElementById('current-step');
    const progressPercent = document.getElementById('progress-percent');
    
    let currentStep = 1;
    const totalSteps = steps.length;

    function updateProgress() {
        const percent = Math.round((currentStep / totalSteps) * 100);
        progressBar.style.width = percent + '%';
        currentStepSpan.textContent = currentStep;
        progressPercent.textContent = percent;
    }

    function showStep(step) {
        steps.forEach((s, index) => {
            s.classList.toggle('active', index + 1 === step);
        });

        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
        submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
        
        updateProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const inputs = currentStepElement.querySelectorAll('input, select, textarea');
        let isValid = true;
        
        // Убираем предыдущие ошибки
        currentStepElement.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        currentStepElement.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        inputs.forEach(input => {
            // Пропускаем скрытые поля и чекбоксы
            if (input.type === 'hidden' || input.type === 'checkbox') return;
            
            // Проверяем required поля
            if (input.hasAttribute('required') && !input.value.trim()) {
                showError(input, 'Это поле обязательно для заполнения');
                isValid = false;
                return;
            }
            
            // Проверяем email
            if (input.type === 'email' && input.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(input.value)) {
                    showError(input, 'Введите корректный email');
                    isValid = false;
                    return;
                }
            }
            
            // Проверяем телефон
            if (input.name === 'phone' && input.value) {
                const phonePattern = /^[\d\s\+\-\(\)]+$/;
                if (!phonePattern.test(input.value) || input.value.replace(/\D/g, '').length < 10) {
                    showError(input, 'Введите корректный номер телефона');
                    isValid = false;
                    return;
                }
            }
            
            // Проверяем числовые поля
            if (input.type === 'number' && input.value) {
                const num = parseInt(input.value);
                if (isNaN(num) || num <= 0) {
                    showError(input, 'Введите корректное число');
                    isValid = false;
                    return;
                }
            }
            
            // Проверяем файлы
            if (input.type === 'file' && input.hasAttribute('required') && !input.files.length) {
                showError(input, 'Загрузите фото');
                isValid = false;
                return;
            }
        });
        
        // Проверяем радио-кнопки
        const radioGroups = new Set();
        currentStepElement.querySelectorAll('input[type="radio"][required]').forEach(radio => {
            radioGroups.add(radio.name);
        });
        
        radioGroups.forEach(groupName => {
            const checked = currentStepElement.querySelector(`input[name="${groupName}"]:checked`);
            if (!checked) {
                const firstRadio = currentStepElement.querySelector(`input[name="${groupName}"]`);
                if (firstRadio) {
                    showError(firstRadio.closest('.form-group') || firstRadio, 'Выберите один из вариантов');
                    isValid = false;
                }
            }
        });
        
        return isValid;
    }
    
    function showError(element, message) {
        element.classList.add('is-invalid');
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        feedback.textContent = message;
        element.parentNode.appendChild(feedback);
        
        // Прокручиваем к первой ошибке
        if (!document.querySelector('.is-invalid:not(' + element + ')')) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    nextBtn.addEventListener('click', function() {
        if (validateCurrentStep() && currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Обработка отправки формы
    form.addEventListener('submit', function(e) {
        const spinner = document.getElementById('submit-spinner');
        const submitText = document.getElementById('submit-text');
        
        // Показываем индикатор загрузки
        spinner.classList.remove('d-none');
        submitText.textContent = 'Отправка...';
        submitBtn.disabled = true;
    });

    // Превью фото
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    
    photoInput.addEventListener('change', function(e) {
        photoPreview.innerHTML = '';
        const files = Array.from(e.target.files).slice(0, 5);
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid" style="aspect-ratio: 1; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 badge bg-dark m-2">${index + 1}</span>
                    </div>
                `;
                photoPreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });

    // Инициализация
    showStep(1);
});
</script>

<style>
.form-step {
    display: none;
}
.form-step.active {
    display: block;
}
.btn-check:checked + .btn-outline-dark {
    background-color: var(--text-primary);
    color: #FFFFFF;
}
</style>
@endpush
