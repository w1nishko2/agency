@extends('layouts.main')

@section('title', 'Подбор моделей - Golden Models')
@section('description', 'Подберите модель по вашим критериям. Заполните анкету и наши менеджеры подберут идеальных кандидатов для вашего проекта.')

@section('content')

<!-- Hero блок -->
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ asset('imgsite/photo/photo_6_2026-01-24_11-43-44.webp') }}') center/cover;">
    <div class="container text-center">
        <h1 class="mb-3">ПОДБОР МОДЕЛЕЙ</h1>
        <p class="lead text-muted">Укажите критерии и мы подберем идеальную модель для вашего проекта</p>
    </div>
</section>

<!-- Форма подбора моделей -->
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
                
                <!-- Прогресс-бар -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Шаг <span id="current-step">1</span> из 14</small>
                        <small class="text-muted"><span id="progress-percent">7</span>%</small>
                    </div>
                    <div class="progress" style="height: 3px;">
                        <div id="progress-bar" class="progress-bar bg-dark" role="progressbar" style="width: 7%"></div>
                    </div>
                </div>

                <form id="casting-form" action="{{ route('casting.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Шаг 1: Тип проекта -->
                    <div class="form-step active" data-step="1">
                        <h3 class="mb-4">Для какого проекта нужна модель?</h3>
                        <select class="form-select form-select-lg" name="project_type" required>
                            <option value="">Выберите тип проекта</option>
                            <option value="Фотосессия">Фотосессия</option>
                            <option value="Видеосъемка">Видеосъемка</option>
                            <option value="Реклама">Реклама</option>
                            <option value="Показ/Дефиле">Показ/Дефиле</option>
                            <option value="Выставка">Выставка</option>
                            <option value="Промо-акция">Промо-акция</option>
                            <option value="Каталог">Каталог</option>
                            <option value="Другое">Другое</option>
                        </select>
                    </div>

                    <!-- Шаг 2: Пол модели -->
                    <div class="form-step" data-step="2">
                        <h3 class="mb-4">Пол модели</h3>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="gender" id="gender-female" value="female" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="gender-female">
                                    <i class="bi bi-gender-female d-block mb-2" style="font-size: 2rem;"></i>
                                    Женский
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="gender" id="gender-male" value="male" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="gender-male">
                                    <i class="bi bi-gender-male d-block mb-2" style="font-size: 2rem;"></i>
                                    Мужской
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="gender" id="gender-any" value="any" required>
                                <label class="btn btn-outline-dark w-100 py-4" for="gender-any">
                                    <i class="bi bi-gender-ambiguous d-block mb-2" style="font-size: 2rem;"></i>
                                    Не важно
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 3: Возраст -->
                    <div class="form-step" data-step="3">
                        <h3 class="mb-4">Желаемый возраст модели</h3>
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
                            <option value="Не важно">Не важно</option>
                        </select>
                    </div>

                    <!-- Шаг 4: Рост -->
                    <div class="form-step" data-step="4">
                        <h3 class="mb-4">Рост модели (см)</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="height_from" class="form-label">От</label>
                                <input type="number" class="form-control form-control-lg" name="height_from" 
                                       id="height_from" placeholder="Например: 165" min="50" max="250">
                            </div>
                            <div class="col-md-6">
                                <label for="height_to" class="form-label">До</label>
                                <input type="number" class="form-control form-control-lg" name="height_to" 
                                       id="height_to" placeholder="Например: 180" min="50" max="250">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-3">Оставьте пустым, если рост не важен</small>
                    </div>

                    <!-- Шаг 5: Размер одежды -->
                    <div class="form-step" data-step="5">
                        <h3 class="mb-4">Размер одежды</h3>
                        <select class="form-select form-select-lg" name="clothing_size" required>
                            <option value="">Выберите размер одежды</option>
                            <option value="XS">XS (40-42)</option>
                            <option value="S">S (42-44)</option>
                            <option value="M">M (44-46)</option>
                            <option value="L">L (46-48)</option>
                            <option value="XL">XL (48-50)</option>
                            <option value="XXL">XXL (50-52)</option>
                            <option value="XXXL">XXXL (52+)</option>
                            <option value="Не важно">Не важно</option>
                        </select>
                    </div>

                    <!-- Шаг 6: Цвет волос -->
                    <div class="form-step" data-step="6">
                        <h3 class="mb-4">Цвет волос</h3>
                        <select class="form-select form-select-lg" name="hair_color" required>
                            <option value="">Выберите цвет волос</option>
                            <option value="Блонд">Блонд</option>
                            <option value="Русый">Русый</option>
                            <option value="Шатен">Шатен</option>
                            <option value="Брюнет">Брюнет</option>
                            <option value="Рыжий">Рыжий</option>
                            <option value="Седой">Седой</option>
                            <option value="Не важно">Не важно</option>
                        </select>
                    </div>

                    <!-- Шаг 7: Цвет глаз -->
                    <div class="form-step" data-step="7">
                        <h3 class="mb-4">Цвет глаз</h3>
                        <select class="form-select form-select-lg" name="eye_color" required>
                            <option value="">Выберите цвет глаз</option>
                            <option value="Карие">Карие</option>
                            <option value="Голубые">Голубые</option>
                            <option value="Зеленые">Зеленые</option>
                            <option value="Серые">Серые</option>
                            <option value="Чёрные">Чёрные</option>
                            <option value="Не важно">Не важно</option>
                        </select>
                    </div>

                    <!-- Шаг 8: Тип внешности -->
                    <div class="form-step" data-step="8">
                        <h3 class="mb-4">Тип внешности</h3>
                        <select class="form-select form-select-lg" name="appearance_type" required>
                            <option value="">Выберите тип внешности</option>
                            <option value="Славянский">Славянский</option>
                            <option value="Европейский">Европейский</option>
                            <option value="Азиатский">Азиатский</option>
                            <option value="Афро">Афро</option>
                            <option value="Мулат">Мулат</option>
                            <option value="Не важно">Не важно</option>
                        </select>
                    </div>

                    <!-- Шаг 9: Знание языков -->
                    <div class="form-step" data-step="9">
                        <h3 class="mb-4">Знание иностранных языков (необязательно)</h3>
                        <p class="text-muted mb-4">Нужны ли знания иностранных языков для проекта?</p>
                        <select class="form-select form-select-lg" name="languages">
                            <option value="">Не важно</option>
                            <option value="Английский">Английский</option>
                            <option value="Немецкий">Немецкий</option>
                            <option value="Французский">Французский</option>
                            <option value="Испанский">Испанский</option>
                            <option value="Итальянский">Итальянский</option>
                            <option value="Китайский">Китайский</option>
                        </select>
                        <small class="text-muted d-block mt-3">Можно оставить пустым, если знание языков не требуется</small>
                    </div>

                    <!-- Шаг 10: Параметры фигуры (Грудь-Талия-Бедра) -->
                    <div class="form-step" data-step="10">
                        <h3 class="mb-4">Параметры фигуры (необязательно)</h3>
                        <p class="text-muted mb-4">Укажите желаемые параметры модели, если это важно для проекта</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="bust" class="form-label">Грудь (см)</label>
                                <input type="number" class="form-control form-control-lg" name="bust" 
                                       id="bust" placeholder="90" min="60" max="150">
                            </div>
                            <div class="col-md-4">
                                <label for="waist" class="form-label">Талия (см)</label>
                                <input type="number" class="form-control form-control-lg" name="waist" 
                                       id="waist" placeholder="60" min="50" max="120">
                            </div>
                            <div class="col-md-4">
                                <label for="hips" class="form-label">Бедра (см)</label>
                                <input type="number" class="form-control form-control-lg" name="hips" 
                                       id="hips" placeholder="90" min="60" max="150">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-3">Оставьте пустым, если параметры не важны</small>
                    </div>

                    <!-- Шаг 11: Размер обуви -->
                    <div class="form-step" data-step="11">
                        <h3 class="mb-4">Размер обуви (необязательно)</h3>
                        <input type="number" class="form-control form-control-lg" name="shoe_size" 
                               placeholder="Например: 38" min="33" max="48" step="0.5">
                        <small class="text-muted d-block mt-3">Оставьте пустым, если размер не важен</small>
                    </div>

                    <!-- Шаг 12: Город съемки -->
                    <div class="form-step" data-step="12">
                        <h3 class="mb-4">Город, где нужна модель</h3>
                        <input type="text" class="form-control form-control-lg" name="city" 
                               placeholder="Например: Москва" required>
                        <small class="text-muted d-block mt-3">Укажите город, где будет проходить съемка/проект</small>
                    </div>

                    <!-- Шаг 13: Детали проекта -->
                    <div class="form-step" data-step="13">
                        <h3 class="mb-4">Дополнительная информация о проекте</h3>
                        <textarea class="form-control form-control-lg" name="project_description" rows="5" 
                                  placeholder="Опишите ваш проект: дата съемки, локация, бюджет, особые требования..." required></textarea>
                        <small class="text-muted d-block mt-2">Чем подробнее описание, тем точнее мы подберем модель</small>
                    </div>

                    <!-- Шаг 14: Контактная информация -->
                    <div class="form-step" data-step="14">
                        <h3 class="mb-4">Ваши контактные данные</h3>
                        
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Ваше имя / Название компании</label>
                            <input type="text" class="form-control form-control-lg" name="client_name" 
                                   id="client_name" placeholder="Иван Иванов" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control form-control-lg" name="phone" 
                                   id="phone" placeholder="+7 (999) 123-45-67" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" name="email" 
                                   id="email" placeholder="example@mail.ru" required>
                        </div>

                        <div class="mb-3">
                            <label for="budget" class="form-label">Бюджет (необязательно)</label>
                            <input type="text" class="form-control form-control-lg" name="budget" 
                                   id="budget" placeholder="от 10 000 руб">
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Что дальше?</strong><br>
                            После отправки заявки наш менеджер свяжется с вами в течение 24 часов и предложит подходящие кандидатуры.
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
                            <span id="submit-text">Отправить заявку</span>
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
            // Пропускаем необязательные поля
            if (!input.hasAttribute('required')) return;
            
            // Проверяем required поля для всех типов
            if (input.type === 'radio' || input.type === 'checkbox') {
                return; // Радио и чекбоксы проверяем отдельно ниже
            }
            
            // Для select проверяем что выбрано значение (не пустая строка)
            if (input.tagName === 'SELECT') {
                if (!input.value || input.value === '') {
                    showError(input, 'Пожалуйста, выберите вариант');
                    isValid = false;
                    return;
                }
            }
            // Для textarea и input
            else if (!input.value || !input.value.trim()) {
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
                    showError(input, 'Введите корректный номер телефона (минимум 10 цифр)');
                    isValid = false;
                    return;
                }
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
                    showError(firstRadio.closest('.row') || firstRadio, 'Выберите один из вариантов');
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
