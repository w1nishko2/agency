@extends('layouts.admin')

@section('title', 'Кастинги')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Кастинги</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Заявки на кастинг и подбор моделей</h2>
</div>

<!-- Фильтры -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <form method="GET" action="{{ route('admin.castings.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Статус</label>
                <select name="status" class="form-select">
                    <option value="">Все</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобренные</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклоненные</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Поиск</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Имя, email, телефон..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i>Найти
                </button>
                <a href="{{ route('admin.castings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Таблица заявок -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="mb-0">
            <i class="bi bi-camera-video me-2"></i>Список заявок
            <span class="badge bg-primary ms-2">{{ $applications->total() }}</span>
        </h5>
    </div>
    <div class="content-card-body p-0">
        @if($applications->count() > 0)
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>Параметры</th>
                        <th>Контакты</th>
                        <th>Дата подачи</th>
                        <th>Статус</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $app->full_name }}</div>
                            <small class="text-muted">
                                @if(str_contains($app->last_name, 'Заявка на подбор'))
                                    <span class="badge badge-sm bg-primary">
                                        <i class="bi bi-search"></i> Подбор модели
                                    </span>
                                @else
                                    {{ $app->gender == 'male' ? 'М' : ($app->gender == 'female' ? 'Ж' : 'Любой') }}
                                    @if($app->age > 0), {{ $app->age }} лет @endif
                                    @if($app->city != '-'), {{ $app->city }} @endif
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($app->height > 0)
                                <span class="fw-semibold">{{ $app->height }} см</span>
                                @if($app->weight > 0) / {{ $app->weight }} кг @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div><i class="bi bi-phone me-1"></i>{{ $app->phone }}</div>
                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $app->email }}</small>
                        </td>
                        <td><small class="text-muted">{{ $app->created_at->format('d.m.Y H:i') }}</small></td>
                        <td>
                            @if($app->status === 'new' || $app->status === 'review')
                                <span class="badge bg-warning text-dark">Новая</span>
                            @elseif($app->status === 'approved')
                                <span class="badge bg-success">Одобрена</span>
                            @elseif($app->status === 'rejected')
                                <span class="badge bg-danger">Отклонена</span>
                            @elseif($app->status === 'contacted')
                                <span class="badge bg-primary">Связались</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons justify-content-end">
                                <a href="{{ route('admin.castings.show', $app->id) }}" class="btn btn-sm btn-outline-primary" title="Просмотр">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($app->status === 'new' || $app->status === 'review')
                                    <form action="{{ route('admin.castings.approve', $app->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Одобрить">
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
            {{ $applications->links() }}
        </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <p class="mb-0">Заявок не найдено</p>
            </div>
        @endif
    </div>
</div>

@endsection
