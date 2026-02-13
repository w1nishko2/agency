@extends('layouts.main')

@section('title', $model->full_name . ' - Golden Models')
@section('description', 'Профессиональная модель ' . $model->full_name . '. Возраст: ' . $model->age . ' ' . ($model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет')) . ', рост: ' . $model->height . ' см.')

@section('content')

<!-- Профиль модели -->
<section class="py-5">
    <div class="container">
        <div class="row">
            
            <!-- Основное фото -->
            <div class="col-lg-5 mb-4">
                <div class="sticky-top" style="top: 100px;">
                    <div class="rounded photo-gallery-item" style="background: #f8f9fa; padding: 10px; cursor: pointer; position: relative; overflow: hidden;">
                    @if($model->photos && count($model->photos) > 0 && Storage::disk('public')->exists($model->photos[0]))
                        <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                             alt="{{ e($model->full_name) }}" 
                             class="img-fluid mb-3 rounded"
                             style="width: 100%; aspect-ratio: 2/3; object-fit: cover; display: block;"
                             onclick="openPhotoSlider(0)">
                    @else
                        <img src="{{ asset('imgsite/placeholder.svg') }}" 
                             alt="Фото отсутствует"
                             class="img-fluid mb-3 rounded"
                             style="width: 100%; height: 400px; object-fit: contain;">
                    @endif
                    </div>
                    
                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-envelope me-2"></i>Пригласить модель
                    </button>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark flex-fill">
                            <i class="bi bi-heart"></i>
                        </button>
                        <button class="btn btn-outline-dark flex-fill">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Информация -->
            <div class="col-lg-7">
                <div class="mb-4">
                    <h1 class="mb-2">{{ strtoupper($model->full_name) }}</h1>
                    @if($model->model_number)
                    <p class="text-muted">
                        Модель № {{ $model->model_number }}
                    </p>
                    @endif
                </div>

                <!-- Основные параметры -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Основные параметры</h5>
                        <div class="row g-3">
                            @if($model->gender)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Пол</div>
                                <div class="fw-semibold">{{ $model->gender == 'female' ? 'Женщина' : 'Мужчина' }}</div>
                            </div>
                            @endif
                            
                            @if($model->age)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Возраст</div>
                                <div class="fw-semibold">{{ $model->age }} {{ $model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет') }}</div>
                            </div>
                            @endif
                            
                            @if($model->city)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Город</div>
                                <div class="fw-semibold">{{ $model->city }}</div>
                            </div>
                            @endif
                            
                            @if($model->height)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Рост</div>
                                <div class="fw-semibold">{{ $model->height }} см</div>
                            </div>
                            @endif
                            
                            @if($model->weight)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Вес</div>
                                <div class="fw-semibold">{{ $model->weight }} кг</div>
                            </div>
                            @endif
                            
                            @if($model->measurements)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Параметры</div>
                                <div class="fw-semibold">{{ $model->measurements }}</div>
                            </div>
                            @endif
                            
                            @if($model->eye_color)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Цвет глаз</div>
                                <div class="fw-semibold">{{ $model->eye_color }}</div>
                            </div>
                            @endif
                            
                            @if($model->hair_color)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Цвет волос</div>
                                <div class="fw-semibold">{{ $model->hair_color }}</div>
                            </div>
                            @endif
                            
                            @if($model->skin_color)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Цвет кожи</div>
                                <div class="fw-semibold">{{ $model->skin_color }}</div>
                            </div>
                            @endif
                            
                            @if($model->appearance_type)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Тип внешности</div>
                                <div class="fw-semibold">{{ $model->appearance_type }}</div>
                            </div>
                            @endif
                            
                            @if($model->shoe_size)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Размер обуви</div>
                                <div class="fw-semibold">{{ $model->shoe_size }}</div>
                            </div>
                            @endif
                            
                            @if($model->clothing_size)
                            <div class="col-6 col-md-4">
                                <div class="text-muted small">Размер одежды</div>
                                <div class="fw-semibold">{{ $model->clothing_size }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- О модели -->
                @if($model->bio)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">О модели</h5>
                        <p class="mb-0">{{ $model->bio }}</p>
                    </div>
                </div>
                @endif

                <!-- Опыт и образование -->
                @if($model->experience_years || $model->experience_description || $model->has_modeling_school)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Опыт и образование</h5>
                        
                        @if($model->experience_years)
                        <div class="mb-2">
                            <span class="text-muted">Опыт работы:</span>
                            <span class="fw-semibold">{{ $model->experience_years }} {{ $model->experience_years == 1 ? 'год' : ($model->experience_years < 5 ? 'года' : 'лет') }}</span>
                        </div>
                        @endif
                        
                        @if($model->experience_description)
                        <div class="mb-2">
                            <p class="mb-0">{{ $model->experience_description }}</p>
                        </div>
                        @endif
                        
                        @if($model->has_modeling_school)
                        <div>
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            <span>Есть образование модельной школы</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Навыки -->
                @if($model->skills && is_array($model->skills) && count($model->skills) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Навыки</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($model->skills as $skill)
                                <span class="badge bg-light text-dark border">
                                    {{ is_array($skill) && isset($skill['skill']) ? $skill['skill'] : $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Языки -->
                @if($model->languages && is_array($model->languages) && count($model->languages) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Языки</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($model->languages as $language)
                                <span class="badge bg-light text-dark border">
                                    {{ is_array($language) && isset($language['language']) ? $language['language'] : $language }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Категории -->
                @if($model->categories && is_array($model->categories) && count($model->categories) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Категории</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($model->categories as $category)
                                <span class="badge bg-dark">
                                    {{ ucfirst(is_array($category) && isset($category['category']) ? $category['category'] : $category) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @elseif($model->category)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Категория</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-dark">{{ ucfirst($model->category) }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Дополнительная информация -->
                @if($model->has_tattoos || $model->has_piercings || $model->has_passport || $model->has_snaps || $model->has_video_presentation || $model->has_video_walk)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Дополнительная информация</h5>
                        <ul class="list-unstyled mb-0">
                            @if($model->has_passport)
                            <li class="mb-1">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Есть паспорт
                            </li>
                            @endif
                            @if($model->has_snaps)
                            <li class="mb-1">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Есть снэпы
                            </li>
                            @endif
                            @if($model->has_video_presentation)
                            <li class="mb-1">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Есть видеопрезентация
                            </li>
                            @endif
                            @if($model->has_video_walk)
                            <li class="mb-1">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Есть видео-походка
                            </li>
                            @endif
                            @if($model->has_tattoos)
                            <li class="mb-1">
                                <i class="bi bi-info-circle-fill text-info me-2"></i>Есть татуировки
                            </li>
                            @endif
                            @if($model->has_piercings)
                            <li class="mb-1">
                                <i class="bi bi-info-circle-fill text-info me-2"></i>Есть пирсинг
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Видео -->
                @if($model->video_url)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Видео</h5>
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $model->video_url }}" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Портфолио -->
                @if($model->photos && count($model->photos) > 1)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Портфолио</h5>
                        <div class="row g-3">
                            @foreach(array_slice($model->photos, 1) as $index => $photo)
                                @if(Storage::disk('public')->exists($photo))
                                <div class="col-6 col-md-4">
                                    <div class="rounded photo-gallery-item" style="background: #f8f9fa; padding: 5px; cursor: pointer; position: relative; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             class="img-fluid rounded" 
                                             style="width: 100%; aspect-ratio: 2/3; object-fit: cover; display: block; transition: transform 0.2s;"
                                             loading="lazy"
                                             onclick="openPhotoSlider({{ $index + 1 }})"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

<!-- Модальное окно приглашения -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Пригласить модель</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('models.book', $model->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Ваше имя *</label>
                        <input type="text" class="form-control" name="client_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Телефон *</label>
                        <input type="tel" class="form-control" name="client_phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="client_email">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Дата мероприятия</label>
                        <input type="date" class="form-control" name="event_date">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Комментарий</label>
                        <textarea class="form-control" name="message" rows="4" 
                                  placeholder="Расскажите о проекте"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Отправить заявку</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно слайдера фотографий -->
<div class="modal fade" id="photoSliderModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0 position-absolute top-0 end-0 z-3">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative">
                <!-- Главное изображение -->
                <div class="photo-slider-container w-100 h-100 d-flex align-items-center justify-content-center">
                    <img id="sliderMainImage" 
                         src="" 
                         alt="Фото модели" 
                         class="img-fluid"
                         style="max-height: 90vh; max-width: 100%; object-fit: contain; user-select: none;">
                </div>

                <!-- Кнопка "Предыдущее" -->
                <button class="btn btn-slider btn-slider-prev" onclick="changeSlide(-1)" aria-label="Предыдущее фото">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <!-- Кнопка "Следующее" -->
                <button class="btn btn-slider btn-slider-next" onclick="changeSlide(1)" aria-label="Следующее фото">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <!-- Индикатор текущей фотографии -->
                <div class="photo-counter position-absolute bottom-0 start-50 translate-middle-x mb-3 px-4 py-2 rounded-pill">
                    <span id="currentPhotoNumber">1</span> / <span id="totalPhotos">1</span>
                </div>

                <!-- Миниатюры внизу (для десктопа) -->
                <div class="photo-thumbnails position-absolute bottom-0 start-0 end-0 mb-5 d-none d-md-block">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-center gap-2 flex-wrap" id="photoThumbnailsContainer">
                            <!-- Миниатюры будут добавлены через JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Стили для слайдера фотографий */
    #photoSliderModal .modal-content {
        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(10px);
    }

    .photo-slider-container {
        touch-action: pan-y pinch-zoom;
    }

    .photo-slider-container img {
        transition: opacity 0.3s ease;
    }

    .btn-slider {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        z-index: 10;
        transition: all 0.3s ease;
        opacity: 0.7;
    }

    .btn-slider:hover {
        background: rgba(255, 255, 255, 0.25);
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
        color: white;
    }

    .btn-slider:active {
        transform: translateY(-50%) scale(0.95);
    }

    .btn-slider-prev {
        left: 20px;
    }

    .btn-slider-next {
        right: 20px;
    }

    .photo-counter {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        color: white;
        font-size: 16px;
        font-weight: 500;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .photo-thumbnails {
        z-index: 10;
        max-width: 100%;
        overflow-x: auto;
        padding: 0 20px;
    }

    .photo-thumbnails::-webkit-scrollbar {
        height: 6px;
    }

    .photo-thumbnails::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 3px;
    }

    .photo-thumbnails::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.4);
        border-radius: 3px;
    }

    .photo-thumbnail {
        width: 80px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid transparent;
        opacity: 0.6;
    }

    .photo-thumbnail:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .photo-thumbnail.active {
        border-color: #fff;
        opacity: 1;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
    }

    /* Адаптив для мобильных */
    @media (max-width: 768px) {
        .btn-slider {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }

        .btn-slider-prev {
            left: 10px;
        }

        .btn-slider-next {
            right: 10px;
        }

        .photo-counter {
            font-size: 14px;
            padding: 8px 16px;
        }

        #sliderMainImage {
            max-height: 85vh;
        }
    }

    @media (max-width: 576px) {
        .btn-slider {
            width: 40px;
            height: 40px;
            font-size: 18px;
            opacity: 0.5;
        }

        .btn-slider:hover {
            opacity: 0.8;
        }

        .photo-counter {
            font-size: 12px;
            padding: 6px 12px;
        }
    }

    /* Эффект при клике на фото в галерее */
    .photo-gallery-item::after {
        content: '\F5E0';
        font-family: 'bootstrap-icons';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 40px;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .photo-gallery-item:hover::after {
        opacity: 0.9;
    }
</style>
@endpush

@push('scripts')
<script>
    // Все фотографии модели
    const modelPhotos = @json(
        collect($model->photos ?? [])
            ->filter(fn($photo) => Storage::disk('public')->exists($photo))
            ->values()
            ->all()
    );
    let currentPhotoIndex = 0;
    let touchStartX = 0;
    let touchEndX = 0;

    // Открыть слайдер с определенной фотографии
    function openPhotoSlider(index) {
        currentPhotoIndex = index;
        updateSliderImage();
        renderThumbnails();
        
        const modalElement = document.getElementById('photoSliderModal');
        if (typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback если Bootstrap еще не загружен
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
        }
        
        // Добавляем обработчики свайпов для мобильных
        const sliderContainer = document.querySelector('.photo-slider-container');
        sliderContainer.addEventListener('touchstart', handleTouchStart, { passive: true });
        sliderContainer.addEventListener('touchend', handleTouchEnd, { passive: true });
    }

    // Обновить изображение в слайдере
    function updateSliderImage() {
        const img = document.getElementById('sliderMainImage');
        const photo = modelPhotos[currentPhotoIndex];
        
        // Плавное исчезновение
        img.style.opacity = '0';
        
        setTimeout(() => {
            img.src = '{{ asset("storage") }}/' + photo;
            img.alt = '{{ e($model->full_name) }} - Фото ' + (currentPhotoIndex + 1);
            
            // Плавное появление
            img.style.opacity = '1';
        }, 150);
        
        // Обновить счетчик
        document.getElementById('currentPhotoNumber').textContent = currentPhotoIndex + 1;
        document.getElementById('totalPhotos').textContent = modelPhotos.length;
        
        // Обновить активную миниатюру
        updateActiveThumbnail();
    }

    // Изменить слайд
    function changeSlide(direction) {
        currentPhotoIndex += direction;
        
        // Зацикливание
        if (currentPhotoIndex >= modelPhotos.length) {
            currentPhotoIndex = 0;
        } else if (currentPhotoIndex < 0) {
            currentPhotoIndex = modelPhotos.length - 1;
        }
        
        updateSliderImage();
    }

    // Перейти к конкретному слайду
    function goToSlide(index) {
        currentPhotoIndex = index;
        updateSliderImage();
    }

    // Рендер миниатюр
    function renderThumbnails() {
        const container = document.getElementById('photoThumbnailsContainer');
        container.innerHTML = '';
        
        modelPhotos.forEach((photo, index) => {
            const img = document.createElement('img');
            img.src = '{{ asset("storage") }}/' + photo;
            img.alt = '{{ e($model->full_name) }} - Фото ' + (index + 1);
            img.className = 'photo-thumbnail' + (index === currentPhotoIndex ? ' active' : '');
            img.onclick = () => goToSlide(index);
            container.appendChild(img);
        });
    }

    // Обновить активную миниатюру
    function updateActiveThumbnail() {
        const thumbnails = document.querySelectorAll('.photo-thumbnail');
        thumbnails.forEach((thumb, index) => {
            if (index === currentPhotoIndex) {
                thumb.classList.add('active');
                // Прокрутить к активной миниатюре
                thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                thumb.classList.remove('active');
            }
        });
    }

    // Обработка свайпов на мобильных
    function handleTouchStart(e) {
        touchStartX = e.changedTouches[0].screenX;
    }

    function handleTouchEnd(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Свайп влево - следующее фото
                changeSlide(1);
            } else {
                // Свайп вправо - предыдущее фото
                changeSlide(-1);
            }
        }
    }

    // Навигация клавиатурой
    document.addEventListener('keydown', function(e) {
        const modalElement = document.getElementById('photoSliderModal');
        const isModalOpen = modalElement && modalElement.classList.contains('show');
        
        if (isModalOpen) {
            if (e.key === 'ArrowLeft') {
                changeSlide(-1);
            } else if (e.key === 'ArrowRight') {
                changeSlide(1);
            } else if (e.key === 'Escape') {
                if (typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                } else {
                    modalElement.classList.remove('show');
                    modalElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            }
        }
    });
</script>
@endpush
