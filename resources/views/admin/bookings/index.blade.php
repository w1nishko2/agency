@extends('layouts.admin')

@section('title', 'Бронирования')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Бронирования</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Управление бронированиями</h2>
</div>

<!-- Фильтры -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Статус</label>
                <select name="status" class="form-select">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Выполнено</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Поиск</label>
                <input type="text" name="search" class="form-control" placeholder="Имя клиента, email, телефон..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i>Найти
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Таблица бронирований -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="mb-0">
            <i class="bi bi-calendar-check me-2"></i>Список бронирований
            <span class="badge bg-primary ms-2">{{ $bookings->total() }}</span>
        </h5>
    </div>
    <div class="content-card-body p-0">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Клиент</th>
                            <th>Модель</th>
                            <th>Мероприятие</th>
                            <th>Дата события</th>
                            <th>Статус</th>
                            <th>Создано</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $booking->client_name }}</div>
                                    @if($booking->company_name)
                                        <small class="text-muted">{{ $booking->company_name }}</small><br>
                                    @endif
                                    <small><i class="bi bi-phone me-1"></i>{{ $booking->client_phone }}</small>
                                </td>
                                <td>
                                    @if($booking->model)
                                        <a href="{{ route('admin.models.show', $booking->model->id) }}" class="text-decoration-none">
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
                                    @if($booking->event_location)
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($booking->event_location, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->event_date)
                                        {{ \Carbon\Carbon::parse($booking->event_date)->format('d.m.Y') }}
                                        @if($booking->event_time)
                                            <br><small class="text-muted">{{ $booking->event_time }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">Ожидает</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-success">Подтверждено</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-info">Выполнено</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-danger">Отменено</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $booking->created_at->format('d.m.Y H:i') }}</small></td>
                                <td>
                                    <div class="action-buttons justify-content-end">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($booking->status == 'pending')
                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Подтвердить">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <p class="mb-0">Бронирований не найдено</p>
            </div>
        @endif
    </div>
</div>

@endsection
