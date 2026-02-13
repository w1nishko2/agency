@extends('layouts.main')

@section('title', 'Каталог моделей - Golden Models')
@section('description', 'Подберите модель для вашего проекта. Более 3000 профессиональных моделей всех категорий.')

@section('content')

<!-- Hero блок -->
<section class="py-5">
    <div class="container">
        <h1 class="mb-3">КАТАЛОГ МОДЕЛЕЙ</h1>
        <p class="lead text-muted">Найдите идеальную модель для вашего проекта</p>
    </div>
</section>

<!-- Фильтры и каталог -->
<section class="py-5">
    <div class="container">
        <div class="row">
            
            <!-- Боковая панель с фильтрами -->
            <div class="col-lg-3 mb-4">
                <!-- Кнопка для мобильных -->
                <button class="btn btn-outline-dark w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                    <i class="bi bi-funnel me-2"></i>Фильтры
                </button>
                
                <div class="collapse d-lg-block" id="filtersCollapse">
                    <div class="sticky-top" style="top: 100px;">
                        <h5 class="text-uppercase mb-4">Фильтры</h5>
                        
                        <form id="filter-form">
                        <!-- Категория -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Категория</label>
                            <select class="form-select" name="category">
                                <option value="">Все категории</option>
                                <option value="women">Девушки</option>
                                <option value="men">Юноши</option>
                                <option value="kids">Дети</option>
                                <option value="plus-size">Plus Size</option>
                                <option value="promo">Промо-модели</option>
                                <option value="hosts">Ведущие</option>
                            </select>
                        </div>

                        <!-- Пол -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Пол</label>
                            <select class="form-select" name="gender">
                                <option value="">Не важно</option>
                                <option value="female">Женский</option>
                                <option value="male">Мужской</option>
                            </select>
                        </div>

                        <!-- Возраст -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Возраст</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="age_from" placeholder="От">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="age_to" placeholder="До">
                                </div>
                            </div>
                        </div>

                        <!-- Рост -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Рост (см)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="height_from" placeholder="От">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="height_to" placeholder="До">
                                </div>
                            </div>
                        </div>

                        <!-- Размер одежды -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Размер одежды</label>
                            <select class="form-select" name="clothing_size">
                                <option value="">Любой</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">XXXL</option>
                            </select>
                        </div>

                        <!-- Размер обуви -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Размер обуви</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="shoe_size_from" placeholder="От" min="33" max="48">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="shoe_size_to" placeholder="До" min="33" max="48">
                                </div>
                            </div>
                        </div>

                        <!-- Типаж -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Типаж внешности</label>
                            <select class="form-select" name="appearance_type">
                                <option value="">Любой</option>
                                <option value="Славянский">Славянский</option>
                                <option value="Европейский">Европейский</option>
                                <option value="Азиатский">Азиатский</option>
                                <option value="Афро">Афро</option>
                                <option value="Мулат">Мулат</option>
                            </select>
                        </div>

                        <!-- Цвет кожи -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Цвет кожи</label>
                            <select class="form-select" name="skin_color">
                                <option value="">Любой</option>
                                <option value="очень светлая">Очень светлая</option>
                                <option value="светлая">Светлая</option>
                                <option value="средняя">Средняя</option>
                                <option value="оливковая">Оливковая</option>
                                <option value="смуглая">Смуглая</option>
                                <option value="темная">Темная</option>
                                <option value="очень темная">Очень темная</option>
                            </select>
                        </div>

                        <!-- Цвет глаз -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Цвет глаз</label>
                            <select class="form-select" name="eye_color">
                                <option value="">Любой</option>
                                <option value="голубые">Голубые</option>
                                <option value="синие">Синие</option>
                                <option value="серые">Серые</option>
                                <option value="серо-голубые">Серо-голубые</option>
                                <option value="зеленые">Зеленые</option>
                                <option value="серо-зеленые">Серо-зеленые</option>
                                <option value="карие">Карие</option>
                                <option value="светло-карие">Светло-карие</option>
                                <option value="темно-карие">Темно-карие</option>
                                <option value="черные">Черные</option>
                                <option value="янтарные">Янтарные</option>
                                <option value="ореховые">Ореховые</option>
                            </select>
                        </div>

                        <!-- Цвет волос -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Цвет волос</label>
                            <select class="form-select" name="hair_color">
                                <option value="">Любой</option>
                                <option value="блонд">Блонд</option>
                                <option value="платиновый блонд">Платиновый блонд</option>
                                <option value="пепельный блонд">Пепельный блонд</option>
                                <option value="золотистый блонд">Золотистый блонд</option>
                                <option value="русый">Русый</option>
                                <option value="светло-русый">Светло-русый</option>
                                <option value="темно-русый">Темно-русый</option>
                                <option value="каштановый">Каштановый</option>
                                <option value="светло-каштановый">Светло-каштановый</option>
                                <option value="темно-каштановый">Темно-каштановый</option>
                                <option value="рыжий">Рыжий</option>
                                <option value="медный">Медный</option>
                                <option value="красный">Красный</option>
                                <option value="черный">Черный</option>
                                <option value="седой">Седой</option>
                                <option value="окрашенный">Окрашенный (нестандартный)</option>
                            </select>
                        </div>

                        <!-- Знание языков -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Знание языков</label>
                            <select class="form-select" name="languages">
                                <option value="">Не важно</option>
                                <option value="Английский">Английский</option>
                                <option value="Немецкий">Немецкий</option>
                                <option value="Французский">Французский</option>
                                <option value="Испанский">Испанский</option>
                                <option value="Итальянский">Итальянский</option>
                                <option value="Китайский">Китайский</option>
                            </select>
                        </div>

                        <!-- Дополнительные критерии -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase mb-3">Дополнительно</label>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_snaps" id="has_snaps">
                                <label class="form-check-label small" for="has_snaps">
                                    Есть снепы
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_video_presentation" id="has_video_presentation">
                                <label class="form-check-label small" for="has_video_presentation">
                                    Есть видео представление
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_video_walk" id="has_video_walk">
                                <label class="form-check-label small" for="has_video_walk">
                                    Есть видео походка
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_passport" id="has_passport">
                                <label class="form-check-label small" for="has_passport">
                                    Загранпаспорт
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_professional_experience" id="has_professional_experience">
                                <label class="form-check-label small" for="has_professional_experience">
                                    Большой опыт съёмок
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_tattoos" id="has_tattoos">
                                <label class="form-check-label small" for="has_tattoos">
                                    Есть татуировки
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_piercings" id="has_piercings">
                                <label class="form-check-label small" for="has_piercings">
                                    Есть пирсинг
                                </label>
                            </div>
                        </div>

                        <!-- Город -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Город</label>
                            <input type="text" class="form-control" name="city" placeholder="Введите город">
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Применить</button>
                        <button type="reset" class="btn btn-outline-dark w-100 mt-2">Сбросить</button>
                    </form>
                </div>
                </div>
            </div>

            <!-- Сетка моделей -->
            <div class="col-lg-9">
                
                <!-- Результаты -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">Найдено: <strong id="total-count">{{ $models->total() }}</strong> <span id="model-word">{{ $models->total() == 1 ? 'модель' : ($models->total() < 5 ? 'модели' : 'моделей') }}</span></p>
                    <form id="sort-form" class="d-inline">
                        <select class="form-select w-auto" name="sort" id="sort-select">
                            <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>Новые</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Популярные</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>По имени</option>
                        </select>
                    </form>
                </div>

                <!-- Карточки моделей -->
                <div class="row g-4" id="models-grid">
                    @forelse($models as $model)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('models.show', $model->id) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative" style="background: #f8f9fa;">
                                    @if($model->photos && count($model->photos) > 0)
                                        <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                                             class="card-img-top loaded" 
                                             style="width: 100%; aspect-ratio: 2/3; object-fit: cover;"
                                             alt="{{ $model->full_name }}"
                                             loading="lazy">
                                    @else
                                        <img src="{{ asset('imgsite/placeholder.svg') }}" 
                                             alt="Фото отсутствует" 
                                             class="card-img-top loaded"
                                             style="width: 100%; height: 300px; object-fit: contain;"
                                             loading="lazy">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $model->full_name }}</h5>
                                    @if($model->model_number)
                                    <p class="text-muted small mb-0">№ {{ $model->model_number }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <p class="text-muted">Модели не найдены. Попробуйте изменить фильтры.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Индикатор загрузки -->
                <div id="loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="text-muted mt-3">Загружаем модели...</p>
                </div>

                <!-- Сообщение об окончании списка -->
                <div id="no-more-models" class="text-center py-5" style="display: none;">
                    <p class="text-muted">Все модели загружены</p>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
    let currentPage = 1;
    let loading = false;
    let hasMorePages = true;
    let filters = {};

    // Infinite Scroll
    window.addEventListener('scroll', function() {
        if (loading || !hasMorePages) return;

        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const clientHeight = document.documentElement.clientHeight;

        // Проверяем, достиг ли пользователь почти конца страницы (за 300px до конца)
        if (scrollTop + clientHeight >= scrollHeight - 300) {
            loadMoreModels();
        }
    });

    // Загрузка дополнительных моделей
    function loadMoreModels() {
        if (loading || !hasMorePages) return;

        loading = true;
        currentPage++;

        // Показываем индикатор загрузки
        document.getElementById('loading').style.display = 'block';

        // Формируем URL с фильтрами и номером страницы
        const params = new URLSearchParams(filters);
        params.append('page', currentPage);

        fetch(`{{ route('models.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Скрываем индикатор загрузки
            document.getElementById('loading').style.display = 'none';

            if (data.models && data.models.length > 0) {
                // Добавляем новые карточки
                const grid = document.getElementById('models-grid');
                data.models.forEach(model => {
                    grid.insertAdjacentHTML('beforeend', createModelCard(model));
                });

                // Проверяем, есть ли ещё страницы
                hasMorePages = data.hasMorePages;
                
                if (!hasMorePages) {
                    document.getElementById('no-more-models').style.display = 'block';
                }
            } else {
                hasMorePages = false;
                document.getElementById('no-more-models').style.display = 'block';
            }

            loading = false;
        })
        .catch(error => {
            console.error('Ошибка загрузки моделей:', error);
            document.getElementById('loading').style.display = 'none';
            loading = false;
        });
    }

    // Создание HTML карточки модели
    function createModelCard(model) {
        // Получаем базовый URL сайта
        const baseUrl = '{{ url('/') }}';
        
        // Формируем правильный путь к фото
        let photoUrl;
        if (model.photos && model.photos.length > 0) {
            photoUrl = `${baseUrl}/storage/${model.photos[0]}`;
        } else {
            photoUrl = `${baseUrl}/imgsite/placeholder.svg`;
        }
        
        const modelName = model.full_name || `${model.first_name || ''} ${model.last_name || ''}`.trim() || 'Без имени';
        
        const photoHtml = `<img src="${photoUrl}" 
                     class="card-img-top loaded" 
                     style="height: 400px; object-fit: contain; background: #f8f9fa;"
                     alt="${modelName}"
                     loading="lazy">`;
        
        return `
            <div class="col-md-6 col-lg-4">
                <a href="${baseUrl}/model/${model.id}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative overflow-hidden">
                            ${photoHtml}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-2">${modelName}</h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-geo-alt me-1"></i>${model.city || 'Не указан'}
                            </p>
                            <div class="d-flex gap-2 flex-wrap small text-muted">
                                ${model.age ? `<span>${model.age} ${getAgeWord(model.age)}</span>` : ''}
                                ${model.age && model.height ? '<span>•</span>' : ''}
                                ${model.height ? `<span>${model.height} см</span>` : ''}
                                ${model.eye_color ? `<span>•</span><span>${model.eye_color} глаза</span>` : ''}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;
    }

    // Склонение слова "год/года/лет"
    function getAgeWord(age) {
        const lastDigit = age % 10;
        const lastTwoDigits = age % 100;
        
        if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
            return 'лет';
        }
        if (lastDigit === 1) {
            return 'год';
        }
        if (lastDigit >= 2 && lastDigit <= 4) {
            return 'года';
        }
        return 'лет';
    }

    // Обработка фильтров
    const filterForm = document.getElementById('filter-form');
    
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    filterForm.addEventListener('reset', function(e) {
        e.preventDefault();
        this.reset();
        filters = {};
        resetModels();
    });

    // Применение фильтров
    function applyFilters() {
        const formData = new FormData(filterForm);
        filters = {};
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }

        resetModels();
    }

    // Сброс списка моделей и загрузка с новыми фильтрами
    function resetModels() {
        currentPage = 1;
        hasMorePages = true;
        loading = false;
        
        document.getElementById('loading').style.display = 'block';
        document.getElementById('no-more-models').style.display = 'none';

        const params = new URLSearchParams(filters);
        params.append('page', 1);

        fetch(`{{ route('models.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';

            // Очищаем сетку
            const grid = document.getElementById('models-grid');
            grid.innerHTML = '';

            // Обновляем счетчик
            const totalCount = data.total || 0;
            document.getElementById('total-count').textContent = totalCount;
            document.getElementById('model-word').textContent = getModelWord(totalCount);

            // Добавляем новые карточки
            if (data.models && data.models.length > 0) {
                data.models.forEach(model => {
                    grid.insertAdjacentHTML('beforeend', createModelCard(model));
                });

                hasMorePages = data.hasMorePages;
            } else {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">Модели не найдены</p></div>';
                hasMorePages = false;
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки моделей:', error);
            document.getElementById('loading').style.display = 'none';
        });
    }

    // Склонение слова "модель/модели/моделей"
    function getModelWord(count) {
        if (count === 1) return 'модель';
        if (count >= 2 && count <= 4) return 'модели';
        return 'моделей';
    }

    // Сортировка
    document.getElementById('sort-select').addEventListener('change', function() {
        filters.sort = this.value;
        resetModels();
    });

    // Обработка загрузки изображений (для изображений без класса loaded)
    function setupLazyLoadImages() {
        document.querySelectorAll('img[loading="lazy"]:not(.loaded)').forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                img.addEventListener('error', function() {
                    this.classList.add('loaded'); // Показываем даже если ошибка
                });
            }
        });
    }

    // Инициализация при загрузке страницы
    setupLazyLoadImages();
</script>
@endpush
