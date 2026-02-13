@extends('layouts.admin')

@section('title', 'Дашборд')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Дашборд</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Обзор</h2>
    <div class="text-muted">
        <i class="bi bi-calendar3 me-2"></i>{{ now()->format('d.m.Y') }}
    </div>
</div>

<!-- Статистические карточки -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-primary">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">Всего моделей</div>
                    <h3 class="mb-0">{{ $total_models }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card stat-warning">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">На модерации</div>
                    <h3 class="mb-0">{{ $pending_models }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.models.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning mt-3 w-100">
                <i class="bi bi-arrow-right me-1"></i>Проверить
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card stat-success">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">Активных моделей</div>
                    <h3 class="mb-0">{{ $active_models }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card stat-info">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">Бронирований</div>
                    <h3 class="mb-0">{{ $bookings_count }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Последние регистрации -->
<div class="content-card mb-4">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Последние регистрации моделей</h5>
        <a href="{{ route('admin.models.index') }}" class="btn btn-sm btn-outline-primary">
            Все модели <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="content-card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Модель</th>
                        <th>Параметры</th>
                        <th>Город</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_models as $model)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($model->main_photo && \Storage::disk('public')->exists($model->main_photo))
                                    <img src="{{ asset('storage/' . $model->main_photo) }}" alt="{{ $model->full_name }}" 
                                         class="rounded me-3" style="width: 45px; height: 67.5px; object-fit: cover;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 67.5px; display: none;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @else
                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 60px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $model->full_name }}</div>
                                    <small class="text-muted">{{ $model->gender == 'female' ? 'Ж' : 'М' }}, {{ $model->age }} лет</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $model->height }} см</span><br>
                            <small class="text-muted">{{ $model->measurements ?? '—' }}</small>
                        </td>
                        <td>{{ $model->city }}</td>
                        <td>
                            @if($model->status == 'pending')
                                <span class="badge bg-warning text-dark">На модерации</span>
                            @elseif($model->status == 'active')
                                <span class="badge bg-success">Активна</span>
                            @else
                                <span class="badge bg-secondary">{{ $model->status }}</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $model->created_at->format('d.m.Y H:i') }}</small></td>
                        <td class="text-end">
                            <a href="{{ route('admin.models.detail', $model->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Нет зарегистрированных моделей
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Табы с данными -->
<ul class="nav nav-tabs nav-fill mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#casting" type="button">
            <i class="bi bi-camera-video me-2"></i>Заявки на кастинг
            @if($new_castings_count > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $new_castings_count }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bookings" type="button">
            <i class="bi bi-calendar-check me-2"></i>Бронирования
            @if($pending_bookings_count > 0)
                <span class="badge bg-info ms-2">{{ $pending_bookings_count }}</span>
            @endif
        </button>
    </li>
</ul>

<!-- Содержимое табов -->
<div class="tab-content">
    
    <!-- Заявки на кастинг -->
    <div class="tab-pane fade show active" id="casting" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-camera-video me-2"></i>Последние заявки на кастинг</h5>
                <a href="{{ route('admin.castings.index') }}" class="btn btn-sm btn-outline-primary">
                    Все заявки <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="content-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ФИО</th>
                                <th>Параметры</th>
                                <th>Контакты</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th class="text-end">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_castings as $application)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $application->full_name }}</div>
                                    <small class="text-muted">{{ $application->age }} лет, {{ $application->city }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $application->height }} см</span> / {{ $application->weight }} кг
                                </td>
                                <td>
                                    <div><i class="bi bi-phone me-1"></i>{{ $application->phone }}</div>
                                    <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $application->email }}</small>
                                </td>
                                <td><small class="text-muted">{{ $application->created_at->format('d.m.Y H:i') }}</small></td>
                                <td>
                                    @if($application->status === 'new')
                                        <span class="badge bg-warning text-dark">Новая</span>
                                    @elseif($application->status === 'approved')
                                        <span class="badge bg-success">Одобрена</span>
                                    @elseif($application->status === 'rejected')
                                        <span class="badge bg-danger">Отклонена</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.castings.show', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Нет заявок
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Бронирования -->
    <div class="tab-pane fade" id="bookings" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Последние бронирования</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                    Все бронирования <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="content-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Клиент</th>
                                <th>Модель</th>
                                <th>Мероприятие</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th class="text-end">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $booking->client_name }}</div>
                                    <small class="text-muted"><i class="bi bi-phone me-1"></i>{{ $booking->client_phone }}</small>
                                </td>
                                <td>
                                    @if($booking->model)
                                        <a href="{{ route('admin.models.detail', $booking->model->id) }}" class="text-decoration-none">
                                            {{ $booking->model->full_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->event_type)
                                        <div class="fw-semibold">{{ $booking->event_type }}</div>
                                    @endif
                                    @if($booking->event_date)
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->event_date)->format('d.m.Y') }}</small>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $booking->created_at->format('d.m.Y H:i') }}</small></td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Ожидает</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="badge bg-success">Подтверждено</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Выполнено</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">Отменено</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Нет бронирований
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
