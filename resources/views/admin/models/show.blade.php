@extends('layouts.admin')

@section('title', 'Просмотр модели')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.index') }}">Модели</a></li>
    <li class="breadcrumb-item active">{{ $model->full_name }}</li>
@endsection

@section('content')

<!-- Шапка с действиями -->
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
            
            <form action="{{ route('admin.models.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Удалить модель безвозвратно?')" class="ms-auto">
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
                @if($model->main_photo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $model->main_photo) }}" class="img-fluid rounded" alt="Главное фото">
                        <p class="text-muted small mt-2 mb-0">Главное фото</p>
                    </div>
                @endif

                @if($model->portfolio_photos && count($model->portfolio_photos) > 0)
                    <h6 class="mt-4 mb-3">Портфолио ({{ count($model->portfolio_photos) }})</h6>
                    <div class="row g-2">
                        @foreach($model->portfolio_photos as $photo)
                            <div class="col-6">
                                <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded" alt="Фото">
                            </div>
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
                    <div class="col-md-6">
                        <label class="text-muted small">Город</label>
                        <p class="mb-0">{{ $model->city }}</p>
                    </div>
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
                    <div class="col-md-4">
                        <label class="text-muted small">Рост</label>
                        <p class="mb-0 fw-semibold">{{ $model->height }} см</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Вес</label>
                        <p class="mb-0">{{ $model->weight ?? '—' }} кг</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Размер обуви</label>
                        <p class="mb-0">{{ $model->shoe_size ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Цвет глаз</label>
                        <p class="mb-0">{{ $model->eye_color }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Цвет волос</label>
                        <p class="mb-0">{{ $model->hair_color }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Параметры</label>
                        <p class="mb-0">{{ $model->measurements ?? '—' }}</p>
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
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $model->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Телефон</label>
                        <p class="mb-0">{{ $model->phone ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Instagram</label>
                        <p class="mb-0">{{ $model->instagram ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Telegram</label>
                        <p class="mb-0">{{ $model->telegram ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">VK</label>
                        <p class="mb-0">{{ $model->vk ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Facebook</label>
                        <p class="mb-0">{{ $model->facebook ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Опыт -->
        @if($model->experience_description)
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Опыт работы</h5>
                </div>
                <div class="content-card-body">
                    <p class="mb-0">{{ $model->experience_description }}</p>
                </div>
            </div>
        @endif
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
                    <p>Укажите причину отклонения заявки для <strong>{{ $model->full_name }}</strong>:</p>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Причина отклонения..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отклонить заявку</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
