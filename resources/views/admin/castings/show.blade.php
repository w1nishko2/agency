@extends('layouts.admin')

@section('title', 'Просмотр заявки')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.castings.index') }}">Кастинги</a></li>
    <li class="breadcrumb-item active">#{{ $application->id }}</li>
@endsection

@section('content')

<!-- Шапка с действиями -->
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h2 class="mb-1">{{ $application->full_name }}</h2>
        <p class="text-muted mb-0">
            <small>ID: {{ $application->id }} | Подана: {{ $application->created_at->format('d.m.Y H:i') }}</small>
        </p>
        @if(str_contains($application->last_name, 'Заявка на подбор'))
            <span class="badge bg-primary mt-2">
                <i class="bi bi-search me-1"></i>Запрос на подбор модели
            </span>
        @endif
    </div>
    <div>
        @if($application->status == 'new' || $application->status == 'review')
            <span class="badge bg-info fs-6">Новая</span>
        @elseif($application->status == 'approved')
            <span class="badge bg-success fs-6">Одобрена</span>
        @elseif($application->status == 'rejected')
            <span class="badge bg-danger fs-6">Отклонена</span>
        @elseif($application->status == 'contacted')
            <span class="badge bg-primary fs-6">Связались</span>
        @endif
    </div>
</div>

<!-- Выбранные модели -->
@if($application->selected_models)
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="mb-0">
            <i class="bi bi-check-circle me-2 text-success"></i>Выбранные модели для кастинга
            <span class="badge bg-success ms-2">{{ count(json_decode($application->selected_models)) }}</span>
        </h5>
    </div>
    <div class="content-card-body">
        <div class="row g-3">
            @foreach(json_decode($application->selected_models) as $selectedModel)
            <div class="col-md-4">
                <div class="card position-relative">
                    <!-- Кнопка удаления -->
                    <form action="{{ route('admin.castings.remove-model', [$application->id, $selectedModel->id]) }}" 
                          method="POST" 
                          class="delete-form position-absolute top-0 end-0 m-2"
                          data-confirm="Удалить модель {{ e($selectedModel->name) }} из кастинга?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.25rem 0.5rem;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                    
                    <div class="card-body">
                        <h6 class="card-title mb-2 pe-4">{{ e($selectedModel->name) }}</h6>
                        <div class="small text-muted">
                            <div><i class="bi bi-person me-1"></i>{{ $selectedModel->age }} лет</div>
                            <div><i class="bi bi-rulers me-1"></i>{{ $selectedModel->height }} см</div>
                            <div class="text-success mt-2">
                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($selectedModel->selected_at)->format('d.m.Y H:i') }}
                            </div>
                        </div>
                        <a href="{{ route('admin.models.detail', $selectedModel->id) }}" class="btn btn-sm btn-outline-primary mt-2 w-100">
                            <i class="bi bi-eye me-1"></i>Просмотр
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Панель действий -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.castings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>К списку
            </a>
            
            <a href="{{ route('admin.castings.find-models', $application->id) }}" class="btn btn-primary">
                <i class="bi bi-search me-1"></i>Подбор моделей по критериям
            </a>
            
            @if($application->status == 'new' || $application->status == 'review')
                <form action="{{ route('admin.castings.approve', $application->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Одобрить
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-lg me-1"></i>Отклонить
                </button>
            @endif

            <form action="{{ route('admin.castings.destroy', $application->id) }}" method="POST" 
                  class="delete-form ms-auto"
                  data-confirm="Удалить заявку безвозвратно?">
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
    <div class="col-lg-5">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-images me-2"></i>Фотографии</h5>
            </div>
            <div class="content-card-body">
                                @if($application->photo_portrait && Storage::disk('public')->exists($application->photo_portrait))
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $application->photo_portrait) }}" 
                                             class="img-fluid rounded" alt="Портрет">
                                        <p class="text-muted small mt-2 mb-0">Портрет</p>
                                    </div>
                                @endif

                                @if($application->photo_full_body && Storage::disk('public')->exists($application->photo_full_body))
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $application->photo_full_body) }}" 
                                             class="img-fluid rounded" alt="В полный рост">
                                        <p class="text-muted small mt-2 mb-0">В полный рост</p>
                                    </div>
                                @endif

                                @if($application->photo_profile && Storage::disk('public')->exists($application->photo_profile))
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $application->photo_profile) }}" 
                                             class="img-fluid rounded" alt="Профиль">
                                        <p class="text-muted small mt-2 mb-0">Профиль</p>
                                    </div>
                                @endif

                                @if(($application->photo_additional_1 && Storage::disk('public')->exists($application->photo_additional_1)) || 
                                    ($application->photo_additional_2 && Storage::disk('public')->exists($application->photo_additional_2)))
                                    <h6 class="mt-4 mb-3">Дополнительные фото</h6>
                                    <div class="row g-2">
                                        @if($application->photo_additional_1 && Storage::disk('public')->exists($application->photo_additional_1))
                                            <div class="col-6">
                                                <img src="{{ asset('storage/' . $application->photo_additional_1) }}" 
                                                     class="img-fluid rounded" alt="Доп. фото 1">
                                            </div>
                                        @endif
                                        @if($application->photo_additional_2 && Storage::disk('public')->exists($application->photo_additional_2))
                                            <div class="col-6">
                                                <img src="{{ asset('storage/' . $application->photo_additional_2) }}" 
                                                     class="img-fluid rounded" alt="Доп. фото 2">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Информация -->
                    <div class="col-lg-7">
                        <!-- Основная информация -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Основная информация</h5>
                            </div>
                            <div class="content-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">ФИО</label>
                                        <p class="mb-0 fw-bold">{{ $application->full_name }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small">Пол</label>
                                        <p class="mb-0">{{ $application->gender == 'male' ? 'Мужской' : 'Женский' }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small">Возраст</label>
                                        <p class="mb-0">{{ $application->age }} лет</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Дата рождения</label>
                                        <p class="mb-0">{{ $application->birth_date->format('d.m.Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Город</label>
                                        <p class="mb-0">{{ $application->city }}</p>
                                    </div>
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
                                        <label class="text-muted small">Телефон</label>
                                        <p class="mb-0">
                                            <i class="bi bi-phone me-2"></i>
                                            <a href="tel:{{ $application->phone }}">{{ $application->phone }}</a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Email</label>
                                        <p class="mb-0">
                                            <i class="bi bi-envelope me-2"></i>
                                            <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                                        </p>
                                    </div>
                                    @if($application->telegram)
                                    <div class="col-md-6">
                                        <label class="text-muted small">Telegram</label>
                                        <p class="mb-0">
                                            <i class="bi bi-telegram me-2"></i>
                                            {{ $application->telegram }}
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
                                        <p class="mb-0">{{ $application->height }} см</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small">Вес</label>
                                        <p class="mb-0">{{ $application->weight }} кг</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small">Обувь</label>
                                        <p class="mb-0">{{ $application->shoe_size }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted small">Одежда</label>
                                        <p class="mb-0">{{ $application->clothing_size }}</p>
                                    </div>
                                    @if($application->bust || $application->waist || $application->hips)
                                    <div class="col-md-4">
                                        <label class="text-muted small">Грудь</label>
                                        <p class="mb-0">{{ $application->bust ?? '-' }} см</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Талия</label>
                                        <p class="mb-0">{{ $application->waist ?? '-' }} см</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Бедра</label>
                                        <p class="mb-0">{{ $application->hips ?? '-' }} см</p>
                                    </div>
                                    @endif
                                    <div class="col-md-4">
                                        <label class="text-muted small">Цвет глаз</label>
                                        <p class="mb-0">{{ $application->eye_color }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Цвет волос</label>
                                        <p class="mb-0">{{ $application->hair_color }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Тон кожи</label>
                                        <p class="mb-0">{{ $application->skin_tone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Опыт -->
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0"><i class="bi bi-star me-2"></i>Опыт и навыки</h5>
                            </div>
                            <div class="content-card-body">
                                <div class="mb-3">
                                    <label class="text-muted small">Опыт работы моделью</label>
                                    <p class="mb-0">{{ $application->has_experience ? 'Да' : 'Нет' }}</p>
                                </div>
                                @if($application->experience_description)
                                <div class="mb-3">
                                    <label class="text-muted small">Описание опыта</label>
                                    <p class="mb-0">{{ $application->experience_description }}</p>
                                </div>
                                @endif
                                <div class="mb-3">
                                    <label class="text-muted small">Обучение в модельной школе</label>
                                    <p class="mb-0">{{ $application->has_modeling_school ? 'Да' : 'Нет' }}</p>
                                </div>
                                @if($application->school_name)
                                <div class="mb-3">
                                    <label class="text-muted small">Название школы</label>
                                    <p class="mb-0">{{ $application->school_name }}</p>
                                </div>
                                @endif
                                @if($application->languages)
                                <div class="mb-3">
                                    <label class="text-muted small">Языки</label>
                                    <p class="mb-0">{{ implode(', ', $application->languages) }}</p>
                                </div>
                                @endif
                                @if($application->special_skills)
                                <div class="mb-3">
                                    <label class="text-muted small">Специальные навыки</label>
                                    <p class="mb-0">{{ implode(', ', $application->special_skills) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($application->about || $application->motivation)
                        <div class="content-card mb-4">
                            <div class="content-card-header">
                                <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Дополнительно</h5>
                            </div>
                            <div class="content-card-body">
                                @if($application->about)
                                <div class="mb-3">
                                    <label class="text-muted small">О себе</label>
                                    <p class="mb-0">{{ $application->about }}</p>
                                </div>
                                @endif
                                @if($application->motivation)
                                <div>
                                    <label class="text-muted small">Мотивация</label>
                                    <p class="mb-0">{{ $application->motivation }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

<!-- Модальное окно отклонения -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Отклонить заявку</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.castings.reject', $application->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Причина отклонения</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отклонить</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
