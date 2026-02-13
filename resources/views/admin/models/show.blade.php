@extends('layouts.admin')

@section('title', 'Просмотр модели')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.index') }}">Модели</a></li>
    <li class="breadcrumb-item active">{{ $model->full_name }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h2 class="mb-1">{{ $model->full_name }}</h2>
        <p class="text-muted mb-0">
            <small>ID: {{ $model->id }} | Зарегистрирована: {{ $model->created_at->format('d.m.Y H:i') }}</small>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        @if($model->status == 'pending')
            <span class="badge bg-warning text-dark fs-6">На модерации</span>
        @elseif($model->status == 'active')
            <span class="badge bg-success fs-6">Активна</span>
        @elseif($model->status == 'inactive')
            <span class="badge bg-secondary fs-6">Неактивна</span>
        @else
            <span class="badge bg-danger fs-6">Отклонена</span>
        @endif
    </div>
</div>

<!-- Панель действий -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.models.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>К списку
            </a>
            
            @if($model->status == 'pending')
                <form action="{{ route('admin.models.approve', $model->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Одобрить
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-lg me-1"></i>Отклонить
                </button>
            @elseif($model->status == 'active')
                <form action="{{ route('admin.models.deactivate', $model->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pause me-1"></i>Деактивировать
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.models.edit', $model->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Редактировать
            </a>
            
            <a href="{{ route('models.show', $model->id) }}" class="btn btn-outline-info" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>Посмотреть на сайте
            </a>
            
            <form action="{{ route('admin.models.destroy', $model->id) }}" method="POST" class="delete-form ms-auto" data-confirm="Удалить модель {{ $model->full_name }} безвозвратно?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i>Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Фотографии -->
    <div class="col-lg-4">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-images me-2"></i>Фотографии</h5>
            </div>
            <div class="content-card-body">
                @php
                    $allPhotos = [];
                    if($model->main_photo && \Storage::disk('public')->exists($model->main_photo)) {
                        $allPhotos[] = ['url' => $model->main_photo, 'label' => 'Главное фото'];
                    }
                    if($model->portfolio_photos && count($model->portfolio_photos) > 0) {
                        foreach($model->portfolio_photos as $index => $photo) {
                            if(\Storage::disk('public')->exists($photo)) {
                                $allPhotos[] = ['url' => $photo, 'label' => 'Портфолио ' . ($index + 1)];
                            }
                        }
                    }
                @endphp

                @if($model->main_photo && \Storage::disk('public')->exists($model->main_photo))
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $model->main_photo) }}" 
                             class="img-fluid rounded photo-gallery-item" 
                             alt="Главное фото"
                             style="max-width: 100%; aspect-ratio: 2/3; object-fit: cover; cursor: pointer;"
                             data-photo-index="0"
                             onclick="openPhotoSlider(0)">
                        <p class="text-muted small mt-2 mb-0">Главное фото</p>
                    </div>
                @endif

                @if($model->portfolio_photos && count($model->portfolio_photos) > 0)
                    <h6 class="mt-4 mb-3">Портфолио ({{ count($model->portfolio_photos) }})</h6>
                    <div class="row g-2">
                        @foreach($model->portfolio_photos as $index => $photo)
                            @if(\Storage::disk('public')->exists($photo))
                                <div class="col-6">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         class="img-fluid rounded photo-gallery-item" 
                                         alt="Фото {{ $index + 1 }}"
                                         style="width: 100%; aspect-ratio: 2/3; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                         data-photo-index="{{ ($model->main_photo ? 1 : 0) + $index }}"
                                         onclick="openPhotoSlider({{ ($model->main_photo ? 1 : 0) + $index }})"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!$model->main_photo && (!$model->portfolio_photos || count($model->portfolio_photos) == 0))
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-image fs-1 opacity-50 d-block mb-2"></i>
                        <p class="mb-0">Фотографии не загружены</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Информация -->
    <div class="col-lg-8">
        <!-- Основная информация -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Основная информация</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    @if($model->model_number)
                        <div class="col-md-6">
                            <label class="text-muted small">Номер модели</label>
                            <p class="mb-0 fw-semibold">{{ $model->model_number }}</p>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="text-muted small">Имя</label>
                        <p class="mb-0 fw-semibold">{{ $model->first_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Фамилия</label>
                        <p class="mb-0 fw-semibold">{{ $model->last_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Пол</label>
                        <p class="mb-0">{{ $model->gender == 'female' ? 'Женский' : 'Мужской' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Возраст</label>
                        <p class="mb-0">{{ $model->age }} лет</p>
                    </div>
                    @if($model->birth_date)
                        <div class="col-md-6">
                            <label class="text-muted small">Дата рождения</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="text-muted small">Город</label>
                        <p class="mb-0">{{ $model->city }}</p>
                    </div>
                    @if($model->categories && is_array($model->categories) && count($model->categories) > 0)
                        <div class="col-12">
                            <label class="text-muted small">Категории</label>
                            <p class="mb-0">
                                @foreach($model->categories as $category)
                                    <span class="badge bg-secondary me-1">{{ $category }}</span>
                                @endforeach
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Параметры -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-rulers me-2"></i>Параметры</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-muted small">Рост</label>
                        <p class="mb-0 fw-semibold">{{ $model->height }} см</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Вес</label>
                        <p class="mb-0">{{ $model->weight ?? '—' }} кг</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Размер обуви</label>
                        <p class="mb-0">{{ $model->shoe_size ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Размер одежды</label>
                        <p class="mb-0">{{ $model->clothing_size ?? '—' }}</p>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Грудь</label>
                        <p class="mb-0">{{ $model->bust ?? '—' }} см</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Талия</label>
                        <p class="mb-0">{{ $model->waist ?? '—' }} см</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Бедра</label>
                        <p class="mb-0">{{ $model->hips ?? '—' }} см</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Параметры</label>
                        <p class="mb-0 fw-semibold">{{ $model->measurements ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Внешность -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-palette me-2"></i>Внешность</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Цвет глаз</label>
                        <p class="mb-0">{{ $model->eye_color }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Цвет волос</label>
                        <p class="mb-0">{{ $model->hair_color }}</p>
                    </div>
                    @if($model->appearance_type)
                        <div class="col-md-6">
                            <label class="text-muted small">Типаж внешности</label>
                            <p class="mb-0">{{ $model->appearance_type }}</p>
                        </div>
                    @endif
                    @if($model->skin_color)
                        <div class="col-md-6">
                            <label class="text-muted small">Цвет кожи</label>
                            <p class="mb-0">{{ $model->skin_color }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Контакты -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Контактная информация</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">
                            @if($model->email)
                                <a href="mailto:{{ $model->email }}">{{ $model->email }}</a>
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Телефон</label>
                        <p class="mb-0">
                            @if($model->phone)
                                <a href="tel:{{ $model->phone }}">{{ $model->phone }}</a>
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    @if($model->telegram)
                        <div class="col-md-4">
                            <label class="text-muted small">Telegram</label>
                            <p class="mb-0">{{ $model->telegram }}</p>
                        </div>
                    @endif
                    @if($model->vk)
                        <div class="col-md-4">
                            <label class="text-muted small">VK</label>
                            <p class="mb-0">{{ $model->vk }}</p>
                        </div>
                    @endif
                    @if($model->instagram)
                        <div class="col-md-4">
                            <label class="text-muted small">Instagram</label>
                            <p class="mb-0">{{ $model->instagram }}</p>
                        </div>
                    @endif
                    @if($model->facebook)
                        <div class="col-md-4">
                            <label class="text-muted small">Facebook</label>
                            <p class="mb-0">{{ $model->facebook }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- О себе -->
        @if($model->bio)
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0"><i class="bi bi-card-text me-2"></i>О себе</h5>
                </div>
                <div class="content-card-body">
                    <p class="mb-0">{{ $model->bio }}</p>
                </div>
            </div>
        @endif

        <!-- Языки и навыки -->
        @if(($model->languages && is_array($model->languages) && count($model->languages) > 0) || ($model->skills && is_array($model->skills) && count($model->skills) > 0))
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0"><i class="bi bi-translate me-2"></i>Языки и навыки</h5>
                </div>
                <div class="content-card-body">
                    <div class="row g-3">
                        @if($model->languages && is_array($model->languages) && count($model->languages) > 0)
                            <div class="col-md-6">
                                <label class="text-muted small">Языки</label>
                                <p class="mb-0">
                                    @foreach($model->languages as $lang)
                                        @if(is_array($lang))
                                            <span class="badge bg-info me-1">
                                                {{ $lang['language'] ?? '' }}
                                                @if(isset($lang['level']))
                                                    ({{ $lang['level'] }})
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-info me-1">{{ $lang }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        @endif
                        @if($model->skills && is_array($model->skills) && count($model->skills) > 0)
                            <div class="col-md-6">
                                <label class="text-muted small">Навыки</label>
                                <p class="mb-0">
                                    @foreach($model->skills as $skill)
                                        <span class="badge bg-success me-1">
                                            {{ is_array($skill) && isset($skill['skill']) ? $skill['skill'] : $skill }}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Опыт и образование -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Опыт и образование</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Опыт работы</label>
                        <p class="mb-0">{{ $model->experience_years ?? 0 }} {{ $model->experience_years == 1 ? 'год' : ($model->experience_years < 5 ? 'года' : 'лет') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Обучение в модельной школе</label>
                        <p class="mb-0">
                            @if($model->has_modeling_school)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Да</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    @if($model->experience_description)
                        <div class="col-12">
                            <label class="text-muted small">Описание опыта</label>
                            <p class="mb-0">{{ $model->experience_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Дополнительная информация</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Снепы</label>
                        <p class="mb-0">
                            @if($model->has_snaps)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Видео-презентация</label>
                        <p class="mb-0">
                            @if($model->has_video_presentation)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Видео-походка</label>
                        <p class="mb-0">
                            @if($model->has_video_walk)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Загранпаспорт</label>
                        <p class="mb-0">
                            @if($model->has_passport)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Профессиональный опыт</label>
                        <p class="mb-0">
                            @if($model->has_professional_experience)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Большой опыт</span>
                            @else
                                <span class="badge bg-secondary">Стандартный</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Татуировки</label>
                        <p class="mb-0">
                            @if($model->has_tattoos)
                                <span class="badge bg-warning"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Пирсинг</label>
                        <p class="mb-0">
                            @if($model->has_piercings)
                                <span class="badge bg-warning"><i class="bi bi-check-lg"></i> Есть</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                    @if($model->video_url)
                        <div class="col-md-8">
                            <label class="text-muted small">Видео URL</label>
                            <p class="mb-0">
                                <a href="{{ $model->video_url }}" target="_blank">{{ $model->video_url }}</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Статистика</h5>
            </div>
            <div class="content-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Просмотров</label>
                        <p class="mb-0 fw-semibold fs-4">{{ $model->views_count ?? 0 }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Бронирований</label>
                        <p class="mb-0 fw-semibold fs-4">{{ $model->bookings_count ?? 0 }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Избранная</label>
                        <p class="mb-0">
                            @if($model->is_featured)
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Да</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно отклонения -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.models.reject', $model->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Отклонить заявку</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Укажите причину отклонения заявки для <strong>{{ e($model->full_name) }}</strong>:</p>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Причина отклонения..." required maxlength="500"></textarea>
                    <small class="text-muted">Максимум 500 символов</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отклонить заявку</button>
                </div>
            </form>
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
    .photo-gallery-item {
        position: relative;
        overflow: hidden;
    }

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

<script>
    // Все фотографии модели
    const modelPhotos = @json($allPhotos);
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
            img.src = '{{ asset("storage") }}/' + photo.url;
            img.alt = photo.label;
            
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
            img.src = '{{ asset("storage") }}/' + photo.url;
            img.alt = photo.label;
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

@endsection
