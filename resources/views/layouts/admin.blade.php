<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ панель') - Golden Models</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        /* Боковое меню */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .sidebar-brand small {
            opacity: 0.8;
            font-size: 0.75rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-section {
            padding: 0.5rem 1.25rem;
            margin-top: 1rem;
        }
        
        .menu-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.6;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }
        
        .sidebar-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 500;
        }
        
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
        }
        
        .sidebar-link i {
            font-size: 1.1rem;
            width: 24px;
            margin-right: 0.75rem;
        }
        
        .sidebar-link .badge {
            margin-left: auto;
        }
        
        /* Основная область */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Хедер */
        .admin-header {
            background: white;
            height: var(--header-height);
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .admin-header .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }
        
        .admin-header .user-menu {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-header .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Контент */
        .admin-content {
            padding: 2rem;
        }
        
        /* Карточки */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-card.stat-primary .stat-icon {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .stat-card.stat-success .stat-icon {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .stat-card.stat-warning .stat-icon {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .stat-card.stat-info .stat-icon {
            background: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }
        
        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .content-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .content-card-body {
            padding: 1.5rem;
        }
        
        /* Таблицы */
        .table-modern {
            margin-bottom: 0;
        }
        
        .table-modern thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 1rem;
        }
        
        .table-modern tbody tr {
            transition: background-color 0.2s;
        }
        
        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table-modern td {
            vertical-align: middle;
            padding: 1rem;
        }
        
        /* Кнопки действий */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .admin-content {
                padding: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Боковое меню -->
    <div class="admin-sidebar">
        <div class="sidebar-brand">
            <h4>Golden Models</h4>
            <small>Панель администратора</small>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Дашборд</span>
            </a>
            
            <div class="menu-section">
                <div class="menu-section-title">Управление</div>
            </div>
            
            <a href="{{ route('admin.models.index') }}" class="sidebar-link {{ request()->routeIs('admin.models.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Модели</span>
                @if($sidebar_stats['pending_models_count'] > 0)
                    <span class="badge bg-warning text-dark">{{ $sidebar_stats['pending_models_count'] }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.castings.index') }}" class="sidebar-link {{ request()->routeIs('admin.castings.*') ? 'active' : '' }}">
                <i class="bi bi-camera-video"></i>
                <span>Кастинги</span>
                @if($sidebar_stats['new_castings_count'] > 0)
                    <span class="badge bg-warning text-dark">{{ $sidebar_stats['new_castings_count'] }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Бронирования</span>
                @if($sidebar_stats['pending_bookings_count'] > 0)
                    <span class="badge bg-info">{{ $sidebar_stats['pending_bookings_count'] }}</span>
                @endif
            </a>
            
            <div class="menu-section">
                <div class="menu-section-title">Контент</div>
            </div>
            
            <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i>
                <span>Блог</span>
            </a>
            
            <a href="{{ route('admin.pages.index') }}" class="sidebar-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Страницы сайта</span>
            </a>
            
            <div class="menu-section">
                <div class="menu-section-title">Настройки</div>
            </div>
            
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Контакты и настройки</span>
            </a>
            
            <div class="menu-section">
                <div class="menu-section-title">Система</div>
            </div>
            
            <a href="{{ route('admin.telegram-bot.index') }}" class="sidebar-link {{ request()->routeIs('admin.telegram-bot.*') ? 'active' : '' }}">
                <i class="bi bi-telegram"></i>
                <span>Telegram Бот</span>
            </a>
            
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Перейти на сайт</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Выйти</span>
                </button>
            </form>
        </nav>
    </div>
    
    <!-- Основная область -->
    <div class="admin-main">
        <!-- Хедер -->
        <header class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @yield('breadcrumbs')
                </ol>
            </nav>
            
            <div class="user-menu">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                            <small class="text-muted">Администратор</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Профиль</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Выйти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Контент -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Подтверждение удаления без inline JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const message = this.dataset.confirm || 'Вы уверены?';
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
