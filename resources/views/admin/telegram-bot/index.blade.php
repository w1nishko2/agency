@extends('layouts.admin')

@section('title', 'Настройки Telegram бота')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item active">Настройки Telegram бота</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-telegram text-primary me-2"></i>Настройки Telegram бота</h2>
        </div>
    </div>
</div>

<div class="row">
    <!-- Форма настроек -->
    <div class="col-lg-8">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0">Основные настройки</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('admin.telegram-bot.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="bot_token" class="form-label">
                            Токен бота <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('bot_token') is-invalid @enderror" 
                            id="bot_token" 
                            name="bot_token" 
                            value="{{ old('bot_token', $settings->bot_token) }}"
                            placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz"
                            required
                        >
                        @error('bot_token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Получите токен у <a href="https://t.me/BotFather" target="_blank">@BotFather</a> в Telegram
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="bot_username" class="form-label">Username бота</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input 
                                type="text" 
                                class="form-control @error('bot_username') is-invalid @enderror" 
                                id="bot_username" 
                                name="bot_username" 
                                value="{{ old('bot_username', $settings->bot_username) }}"
                                placeholder="your_bot_username"
                            >
                            @error('bot_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Заполнится автоматически при тестировании подключения
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="admin_telegram_id" class="form-label">Telegram ID администратора</label>
                        <input 
                            type="text" 
                            class="form-control @error('admin_telegram_id') is-invalid @enderror" 
                            id="admin_telegram_id" 
                            name="admin_telegram_id" 
                            value="{{ old('admin_telegram_id', $settings->admin_telegram_id) }}"
                            placeholder="123456789"
                        >
                        @error('admin_telegram_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Узнать свой ID можно у бота <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a>. Используется для отправки уведомлений
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="welcome_message" class="form-label">Приветственное сообщение</label>
                        <textarea 
                            class="form-control @error('welcome_message') is-invalid @enderror" 
                            id="welcome_message" 
                            name="welcome_message" 
                            rows="4"
                            placeholder="Привет! Я бот агентства Golden Models..."
                        >{{ old('welcome_message', $settings->welcome_message) }}</textarea>
                        @error('welcome_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Это сообщение получат пользователи при запуске бота
                        </small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="is_active" 
                                name="is_active"
                                {{ old('is_active', $settings->is_active) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="is_active">
                                Бот активен
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Если выключено, бот не будет отвечать на сообщения
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Сохранить настройки
                        </button>
                        
                        @if($settings->exists)
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('test-form').submit()">
                                <i class="bi bi-lightning me-1"></i>Тестировать подключение
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Настройка Webhook -->
        @if($settings->exists && $settings->isConfigured())
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0">Настройка Webhook</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('admin.telegram-bot.webhook.set') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="webhook_url" class="form-label">
                            URL для Webhook <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="url" 
                            class="form-control @error('webhook_url') is-invalid @enderror" 
                            id="webhook_url" 
                            name="webhook_url" 
                            value="{{ old('webhook_url', $settings->webhook_url ?? url('/api/telegram/webhook')) }}"
                            placeholder="https://your-domain.com/api/telegram/webhook"
                            required
                        >
                        @error('webhook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            URL должен быть доступен из интернета и использовать HTTPS
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-cloud-arrow-up me-1"></i>Установить Webhook
                        </button>
                        
                        @if($settings->webhook_url)
                            <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Удалить webhook?')) document.getElementById('delete-webhook-form').submit()">
                                <i class="bi bi-trash me-1"></i>Удалить Webhook
                            </button>
                        @endif

                        <button type="button" class="btn btn-outline-info" onclick="refreshWebhookInfo()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Обновить информацию
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Информационная панель -->
    <div class="col-lg-4">
        <!-- Статус бота -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0">Статус бота</h5>
            </div>
            <div class="content-card-body">
                @if($settings->exists && $settings->isConfigured())
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Бот настроен</strong>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Бот:</small>
                        <div>
                            @if($settings->bot_username)
                                <a href="https://t.me/{{ $settings->bot_username }}" target="_blank" class="text-decoration-none">
                                    <strong>@{{ $settings->bot_username }}</strong>
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @else
                                <span class="text-muted">Не указан</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Статус:</small>
                        <div>
                            @if($settings->is_active)
                                <span class="badge bg-success">Активен</span>
                            @else
                                <span class="badge bg-secondary">Неактивен</span>
                            @endif
                        </div>
                    </div>

                    @if($settings->admin_telegram_id)
                        <div class="mb-2">
                            <small class="text-muted">ID администратора:</small>
                            <div>
                                <code>{{ $settings->admin_telegram_id }}</code>
                            </div>
                        </div>
                    @endif

                    @if($settings->webhook_url)
                        <div class="mb-2">
                            <small class="text-muted">Webhook:</small>
                            <div>
                                <span class="badge bg-info">Установлен</span>
                            </div>
                        </div>
                    @endif

                    @if($settings->last_webhook_check)
                        <div class="mb-2">
                            <small class="text-muted">Последняя проверка:</small>
                            <div class="small">{{ $settings->last_webhook_check->diffForHumans() }}</div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Бот не настроен</strong>
                        <p class="mb-0 small mt-2">Заполните токен бота для начала работы</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Webhook информация -->
        @if($webhookInfo)
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="mb-0">Информация о Webhook</h5>
            </div>
            <div class="content-card-body">
                <div class="mb-2">
                    <small class="text-muted">URL:</small>
                    <div class="small text-break">
                        {{ $webhookInfo['url'] ?? 'Не установлен' }}
                    </div>
                </div>

                @if(isset($webhookInfo['pending_update_count']))
                    <div class="mb-2">
                        <small class="text-muted">Ожидающих обновлений:</small>
                        <div>
                            <span class="badge bg-{{ $webhookInfo['pending_update_count'] > 0 ? 'warning' : 'success' }}">
                                {{ $webhookInfo['pending_update_count'] }}
                            </span>
                        </div>
                    </div>
                @endif

                @if(isset($webhookInfo['last_error_date']))
                    <div class="mb-2">
                        <small class="text-muted text-danger">Последняя ошибка:</small>
                        <div class="small text-danger">
                            {{ $webhookInfo['last_error_message'] ?? 'Неизвестная ошибка' }}
                        </div>
                        <div class="small text-muted">
                            {{ \Carbon\Carbon::createFromTimestamp($webhookInfo['last_error_date'])->diffForHumans() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Инструкция -->
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="mb-0">Инструкция</h5>
            </div>
            <div class="content-card-body">
                <ol class="mb-0 ps-3">
                    <li class="mb-2">Создайте бота через <a href="https://t.me/BotFather" target="_blank">@BotFather</a></li>
                    <li class="mb-2">Скопируйте полученный токен и вставьте в поле выше</li>
                    <li class="mb-2">Сохраните настройки и протестируйте подключение</li>
                    <li class="mb-2">Установите webhook для получения сообщений</li>
                    <li class="mb-0">Активируйте бота переключателем "Бот активен"</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Скрытые формы -->
@if($settings->exists && $settings->isConfigured())
    <form id="test-form" action="{{ route('admin.telegram-bot.test') }}" method="POST" class="d-none">
        @csrf
    </form>

    <form id="delete-webhook-form" action="{{ route('admin.telegram-bot.webhook.delete') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif

@endsection

@push('scripts')
<script>
function refreshWebhookInfo() {
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Обновление...';
    
    // Просто перезагружаем страницу для обновления информации
    fetch('{{ route("admin.telegram-bot.webhook.info") }}')
        .then(() => {
            window.location.reload();
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            alert('Ошибка при получении информации');
        });
}
</script>
@endpush
