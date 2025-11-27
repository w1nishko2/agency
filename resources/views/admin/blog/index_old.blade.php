@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-md-block bg-light sidebar">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Панель управления
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.blog.index') }}">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Блог
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.models.index') }}">
                            <i class="bi bi-people me-2"></i>
                            Модели
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Управление блогом</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>
                        Создать статью
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Фильтры -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.blog.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="Поиск..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">Все статусы</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Черновик</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Опубликовано</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="category">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary">Фильтр</button>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Сброс</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Таблица статей -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 80px;">Обложка</th>
                            <th>Заголовок</th>
                            <th style="width: 150px;">Категория</th>
                            <th style="width: 120px;">Автор</th>
                            <th style="width: 100px;">Статус</th>
                            <th style="width: 120px;">Дата публикации</th>
                            <th style="width: 80px;">Просмотры</th>
                            <th style="width: 150px;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ Str::limit($post->title, 50) }}</strong>
                                @if($post->is_featured)
                                    <span class="badge bg-warning text-dark ms-2">Featured</span>
                                @endif
                            </td>
                            <td>
                                @if($post->category)
                                    <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($post->author)
                                    {{ $post->author->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge bg-success">Опубликовано</span>
                                @elseif($post->status === 'pending')
                                    <span class="badge bg-warning text-dark">На модерации</span>
                                @else
                                    <span class="badge bg-secondary">Черновик</span>
                                @endif
                            </td>
                            <td>
                                @if($post->published_at)
                                    {{ $post->published_at->format('d.m.Y H:i') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ number_format($post->views_count ?? 0, 0, ',', ' ') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('blog.show', $post->slug) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       target="_blank"
                                       title="Просмотр">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.blog.edit', $post->id) }}" 
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $post->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Удалить статью?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Удалить">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <p>Статьи не найдены</p>
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                                    Создать первую статью
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            @if($posts->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
.sidebar {
    position: fixed;
    top: 56px;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 56px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto;
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .nav-link.active {
    color: #0d6efd;
}

.sidebar .nav-link:hover {
    color: #0d6efd;
}

main {
    margin-left: 16.66667%;
}

@media (max-width: 767.98px) {
    .sidebar {
        position: relative;
        top: 0;
    }
    
    main {
        margin-left: 0;
    }
}
</style>
@endpush
