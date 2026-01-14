@extends('layouts.admin')

@section('title', 'Просмотр бронирования')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Бронирования</a></li>
    <li class="breadcrumb-item active">#{{ $booking->id }}</li>
@endsection

@section('content')

<!-- Шапка с действиями -->
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h2 class="mb-1">Бронирование #{{ $booking->id }}</h2>
        <p class="text-muted mb-0">
            <small>Создано: {{ $booking->created_at->format('d.m.Y H:i') }}</small>
        </p>
    </div>
    <div>
        @if($booking->status == 'pending')
            <span class="badge bg-warning text-dark fs-6">Ожидает</span>
        @elseif($booking->status == 'confirmed')
            <span class="badge bg-success fs-6">Подтверждено</span>
        @elseif($booking->status == 'completed')
            <span class="badge bg-info fs-6">Выполнено</span>
        @elseif($booking->status == 'cancelled')
            <span class="badge bg-danger fs-6">Отменено</span>
        @endif
    </div>
</div>

<!-- Панель действий -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>К списку
            </a>
            
            @if($booking->status == 'pending')
                <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Подтвердить
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-lg me-1"></i>Отменить
                </button>
            @elseif($booking->status == 'confirmed')
                <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-check-circle me-1"></i>Завершить
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" 
                  class="delete-form ms-auto"
                  data-confirm="Удалить бронирование безвозвратно?">
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
    <!-- Информация о клиенте -->
    <div class="col-lg-6">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Информация о клиенте</h5>
            </div>
            <div class="content-card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="text-muted small">Имя клиента</label>
                                        <p class="mb-0 fw-bold">{{ $booking->client_name }}</p>
                                    </div>
                                    @if($booking->company_name)
                                        <div class="col-12">
                                            <label class="text-muted small">Компания</label>
                                            <p class="mb-0">{{ $booking->company_name }}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label class="text-muted small">Телефон</label>
                                        <p class="mb-0">
                                            <i class="bi bi-phone me-2"></i>
                                            <a href="tel:{{ $booking->client_phone }}">{{ $booking->client_phone }}</a>
                                        </p>
                                    </div>
                                    @if($booking->client_email)
                                        <div class="col-md-6">
                                            <label class="text-muted small">Email</label>
                                            <p class="mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                <a href="mailto:{{ $booking->client_email }}">{{ $booking->client_email }}</a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о модели -->
                    <div class="col-lg-6">
                        <div class="content-card h-100">
                            <div class="content-card-header">
                                <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Информация о модели</h5>
                            </div>
                            <div class="content-card-body">
                                @if($booking->model)
                                    <div class="d-flex align-items-center mb-3">
                                        @if($booking->model->main_photo && Storage::disk('public')->exists($booking->model->main_photo))
                                            <img src="{{ asset('storage/' . $booking->model->main_photo) }}" 
                                                 alt="{{ $booking->model->full_name }}" 
                                                 class="rounded me-3" 
                                                 style="width: 80px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 100px;">
                                                <i class="bi bi-person fs-3 text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="mb-1">{{ $booking->model->full_name }}</h5>
                                            <p class="text-muted mb-1">{{ $booking->model->gender == 'female' ? 'Женщина' : 'Мужчина' }}, {{ $booking->model->age }} лет</p>
                                            <p class="mb-0">Рост: {{ $booking->model->height }} см</p>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.models.detail', $booking->model->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-2"></i>Просмотреть профиль модели
                                        </a>
                                        <a href="{{ route('models.show', $booking->model->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>Открыть на сайте
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted">Модель не указана или удалена</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Детали мероприятия -->
                    <div class="col-12">
                        <div class="content-card">
                            <div class="content-card-header">
                                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Детали мероприятия</h5>
                            </div>
                            <div class="content-card-body">
                                <div class="row g-3">
                                    @if($booking->event_type)
                                        <div class="col-md-4">
                                            <label class="text-muted small">Тип мероприятия</label>
                                            <p class="mb-0">{{ $booking->event_type }}</p>
                                        </div>
                                    @endif
                                    @if($booking->event_date)
                                        <div class="col-md-4">
                                            <label class="text-muted small">Дата</label>
                                            <p class="mb-0">
                                                <i class="bi bi-calendar me-2"></i>
                                                {{ \Carbon\Carbon::parse($booking->event_date)->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($booking->event_time)
                                        <div class="col-md-4">
                                            <label class="text-muted small">Время</label>
                                            <p class="mb-0">
                                                <i class="bi bi-clock me-2"></i>
                                                {{ $booking->event_time }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($booking->event_location)
                                        <div class="col-md-6">
                                            <label class="text-muted small">Место проведения</label>
                                            <p class="mb-0">
                                                <i class="bi bi-geo-alt me-2"></i>
                                                {{ $booking->event_location }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($booking->duration_hours)
                                        <div class="col-md-3">
                                            <label class="text-muted small">Длительность</label>
                                            <p class="mb-0">{{ $booking->duration_hours }} ч.</p>
                                        </div>
                                    @endif
                                    @if($booking->budget)
                                        <div class="col-md-3">
                                            <label class="text-muted small">Бюджет</label>
                                            <p class="mb-0">{{ number_format($booking->budget, 0, ',', ' ') }} ₽</p>
                                        </div>
                                    @endif
                                    @if($booking->event_description)
                                        <div class="col-12">
                                            <label class="text-muted small">Описание</label>
                                            <p class="mb-0">{{ e($booking->event_description) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Причина отмены (если есть) -->
                    @if($booking->status == 'cancelled' && $booking->cancellation_reason)
                        <div class="col-12">
                            <div class="content-card border-danger">
                                <div class="content-card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Причина отмены</h5>
                                </div>
                                <div class="content-card-body">
                                    <p class="mb-0">{{ e($booking->cancellation_reason) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

<!-- Модальное окно отмены -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Отменить бронирование</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Укажите причину отмены бронирования #{{ $booking->id }}:</p>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Причина отмены..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отменить бронирование</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
