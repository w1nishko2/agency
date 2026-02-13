@extends('layouts.admin')

@section('title', 'Управление страницами')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Страницы сайта</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Управление страницами</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="content-card">
    <div class="content-card-header">
        <h5 class="mb-0">Список страниц</h5>
    </div>
    <div class="content-card-body p-0">
        
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Slug</th>
                        <th>Статус</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $page->title }}</div>
                                @if($page->meta_title)
                                    <small class="text-muted">SEO: {{ $page->meta_title }}</small>
                                @endif
                            </td>
                            <td>
                                <code>/{{ $page->slug }}</code>
                            </td>
                            <td>
                                @if($page->is_active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Активна
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Неактивна
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pages.edit', $page->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Редактировать
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Страницы не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>

@endsection
