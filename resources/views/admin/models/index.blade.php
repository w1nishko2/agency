@extends('layouts.admin')

@section('title', 'Модели')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Модели</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Управление моделями</h2>
</div>

<!-- Фильтры -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <form action="{{ route('admin.models.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Статус</label>
                <select name="status" class="form-select">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклоненные</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Поиск</label>
                <input type="text" name="search" class="form-control" placeholder="Имя, email, телефон..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i>Найти
                </button>
                <a href="{{ route('admin.models.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Таблица моделей -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="mb-0">
            <i class="bi bi-people me-2"></i>Список моделей
            <span class="badge bg-primary ms-2">{{ $models->total() }}</span>
        </h5>
    </div>
    <div class="content-card-body p-0">
        @if($models->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Модель</th>
                            <th>Параметры</th>
                            <th>Контакты</th>
                            <th>Статус</th>
                            <th>Дата регистрации</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($models as $model)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($model->main_photo)
                                            <img src="{{ asset('storage/' . $model->main_photo) }}" alt="{{ $model->full_name }}" 
                                                 class="rounded me-3" style="width: 50px; height: 65px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 65px;">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $model->full_name }}</div>
                                            <small class="text-muted">{{ $model->gender == 'female' ? 'Ж' : 'М' }}, {{ $model->age }} лет, {{ $model->city }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $model->height }} см</span><br>
                                    <small class="text-muted">{{ $model->measurements ?? '—' }}</small>
                                </td>
                                <td>
                                    @if($model->email)
                                        <div><i class="bi bi-envelope me-1"></i><small>{{ $model->email }}</small></div>
                                    @endif
                                    @if($model->phone)
                                        <div><i class="bi bi-phone me-1"></i><small>{{ $model->phone }}</small></div>
                                    @endif
                                </td>
                                <td>
                                    @if($model->status == 'pending')
                                        <span class="badge bg-warning text-dark">На модерации</span>
                                    @elseif($model->status == 'active')
                                        <span class="badge bg-success">Активна</span>
                                    @elseif($model->status == 'inactive')
                                        <span class="badge bg-secondary">Неактивна</span>
                                    @else
                                        <span class="badge bg-danger">Отклонена</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $model->created_at->format('d.m.Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="action-buttons justify-content-end">
                                        <a href="{{ route('admin.models.show', $model->id) }}" class="btn btn-sm btn-outline-primary" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($model->status == 'pending')
                                            <form action="{{ route('admin.models.approve', $model->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Одобрить">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('admin.models.edit', $model->id) }}" class="btn btn-sm btn-outline-secondary" title="Редактировать">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $models->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <p class="mb-0">Моделей не найдено</p>
            </div>
        @endif
    </div>
</div>

@endsection
