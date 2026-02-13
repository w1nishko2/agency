@extends('layouts.admin')

@section('title', 'Редактирование модели')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.index') }}">Модели</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.detail', $model->id) }}">{{ $model->full_name }}</a></li>
    <li class="breadcrumb-item active">Редактирование</li>
@endsection

@section('content')

<h2 class="mb-4">Редактирование модели: {{ $model->full_name }}</h2>

<div class="content-card mb-4">
    <div class="content-card-body">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.models.detail', $model->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>К профилю
            </a>
            <a href="{{ route('admin.models.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-list me-1"></i>К списку моделей
            </a>
        </div>
    </div>
</div>

<form id="model-edit-form" action="{{ route('admin.models.update', $model->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="mb-0">Основная информация</h5>
        </div>
        <div class="content-card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Порядковый номер</label>
                                    <input type="text" class="form-control @error('model_number') is-invalid @enderror" 
                                           name="model_number" value="{{ old('model_number', $model->model_number) }}" 
                                           placeholder="GM00001">
                                    <small class="text-muted">Оставьте пустым для автогенерации</small>
                                    @error('model_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Имя *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           name="first_name" value="{{ old('first_name', $model->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Фамилия *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           name="last_name" value="{{ old('last_name', $model->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $model->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Телефон *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $model->phone) }}" 
                                           placeholder="+7 (999) 123-45-67">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Пол *</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="female" {{ old('gender', $model->gender) == 'female' ? 'selected' : '' }}>Женский</option>
                                        <option value="male" {{ old('gender', $model->gender) == 'male' ? 'selected' : '' }}>Мужской</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Возраст *</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           name="age" value="{{ old('age', $model->age) }}" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Город *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           name="city" value="{{ old('city', $model->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="mb-0">Параметры</h5>
                        </div>
                        <div class="content-card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Рост (см) *</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           name="height" value="{{ old('height', $model->height) }}" min="150" max="220" required>
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Вес (кг) *</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                           name="weight" value="{{ old('weight', $model->weight) }}" min="40" max="150" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Размер обуви</label>
                                    <input type="number" class="form-control @error('shoe_size') is-invalid @enderror" 
                                           name="shoe_size" value="{{ old('shoe_size', $model->shoe_size) }}" min="30" max="50">
                                    @error('shoe_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Размер одежды</label>
                                    <select class="form-select @error('clothing_size') is-invalid @enderror" name="clothing_size">
                                        <option value="">Не указан</option>
                                        <option value="XS" {{ old('clothing_size', $model->clothing_size) == 'XS' ? 'selected' : '' }}>XS (40-42)</option>
                                        <option value="S" {{ old('clothing_size', $model->clothing_size) == 'S' ? 'selected' : '' }}>S (42-44)</option>
                                        <option value="M" {{ old('clothing_size', $model->clothing_size) == 'M' ? 'selected' : '' }}>M (44-46)</option>
                                        <option value="L" {{ old('clothing_size', $model->clothing_size) == 'L' ? 'selected' : '' }}>L (46-48)</option>
                                        <option value="XL" {{ old('clothing_size', $model->clothing_size) == 'XL' ? 'selected' : '' }}>XL (48-50)</option>
                                        <option value="XXL" {{ old('clothing_size', $model->clothing_size) == 'XXL' ? 'selected' : '' }}>XXL (50-52)</option>
                                        <option value="XXXL" {{ old('clothing_size', $model->clothing_size) == 'XXXL' ? 'selected' : '' }}>XXXL (52+)</option>
                                    </select>
                                    @error('clothing_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Грудь (см)</label>
                                    <input type="number" class="form-control @error('bust') is-invalid @enderror" 
                                           name="bust" value="{{ old('bust', $model->bust) }}">
                                    @error('bust')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Талия (см)</label>
                                    <input type="number" class="form-control @error('waist') is-invalid @enderror" 
                                           name="waist" value="{{ old('waist', $model->waist) }}">
                                    @error('waist')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Бедра (см)</label>
                                    <input type="number" class="form-control @error('hips') is-invalid @enderror" 
                                           name="hips" value="{{ old('hips', $model->hips) }}">
                                    @error('hips')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Цвет глаз *</label>
                                    <select class="form-select @error('eye_color') is-invalid @enderror" name="eye_color" required>
                                        <option value="Карие" {{ old('eye_color', $model->eye_color) == 'Карие' ? 'selected' : '' }}>Карие</option>
                                        <option value="Голубые" {{ old('eye_color', $model->eye_color) == 'Голубые' ? 'selected' : '' }}>Голубые</option>
                                        <option value="Зелёные" {{ old('eye_color', $model->eye_color) == 'Зелёные' ? 'selected' : '' }}>Зелёные</option>
                                        <option value="Серые" {{ old('eye_color', $model->eye_color) == 'Серые' ? 'selected' : '' }}>Серые</option>
                                    </select>
                                    @error('eye_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Цвет волос *</label>
                                    <select class="form-select @error('hair_color') is-invalid @enderror" name="hair_color" required>
                                        <option value="Блонд" {{ old('hair_color', $model->hair_color) == 'Блонд' ? 'selected' : '' }}>Блонд</option>
                                        <option value="Русый" {{ old('hair_color', $model->hair_color) == 'Русый' ? 'selected' : '' }}>Русый</option>
                                        <option value="Каштановый" {{ old('hair_color', $model->hair_color) == 'Каштановый' ? 'selected' : '' }}>Каштановый</option>
                                        <option value="Рыжий" {{ old('hair_color', $model->hair_color) == 'Рыжий' ? 'selected' : '' }}>Рыжий</option>
                                        <option value="Чёрный" {{ old('hair_color', $model->hair_color) == 'Чёрный' ? 'selected' : '' }}>Чёрный</option>
                                    </select>
                                    @error('hair_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Тип внешности</label>
                                    <select class="form-select @error('appearance_type') is-invalid @enderror" name="appearance_type">
                                        <option value="">Не указан</option>
                                        <option value="Славянский" {{ old('appearance_type', $model->appearance_type) == 'Славянский' ? 'selected' : '' }}>Славянский</option>
                                        <option value="Европейский" {{ old('appearance_type', $model->appearance_type) == 'Европейский' ? 'selected' : '' }}>Европейский</option>
                                        <option value="Азиатский" {{ old('appearance_type', $model->appearance_type) == 'Азиатский' ? 'selected' : '' }}>Азиатский</option>
                                        <option value="Афро" {{ old('appearance_type', $model->appearance_type) == 'Афро' ? 'selected' : '' }}>Афро</option>
                                        <option value="Мулат" {{ old('appearance_type', $model->appearance_type) == 'Мулат' ? 'selected' : '' }}>Мулат</option>
                                    </select>
                                    @error('appearance_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Знание языков</label>
                                    <input type="text" class="form-control @error('languages') is-invalid @enderror" 
                                           name="languages" value="{{ old('languages', $model->languages ? collect($model->languages)->pluck('language')->implode(', ') : '') }}" 
                                           placeholder="Например: Английский, Немецкий">
                                    <small class="text-muted">Введите языки через запятую</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="mb-0">Дополнительная информация</h5>
                        </div>
                        <div class="content-card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">О себе</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              name="bio" rows="4">{{ old('bio', $model->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Описание опыта работы</label>
                                    <textarea class="form-control @error('experience_description') is-invalid @enderror" 
                                              name="experience_description" rows="4" 
                                              placeholder="Опишите опыт работы модели...">{{ old('experience_description', $model->experience_description) }}</textarea>
                                    @error('experience_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Telegram</label>
                                    <input type="text" class="form-control @error('telegram') is-invalid @enderror" 
                                           name="telegram" value="{{ old('telegram', $model->telegram) }}" placeholder="@username">
                                    @error('telegram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">VK</label>
                                    <input type="text" class="form-control @error('vk') is-invalid @enderror" 
                                           name="vk" value="{{ old('vk', $model->vk) }}" placeholder="@username">
                                    @error('vk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="mb-0">Статус модерации</h5>
                        </div>
                        <div class="content-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Статус *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="pending" {{ old('status', $model->status) == 'pending' ? 'selected' : '' }}>На модерации</option>
                                        <option value="active" {{ old('status', $model->status) == 'active' ? 'selected' : '' }}>Активна</option>
                                        <option value="inactive" {{ old('status', $model->status) == 'inactive' ? 'selected' : '' }}>Неактивна</option>
                                        <option value="rejected" {{ old('status', $model->status) == 'rejected' ? 'selected' : '' }}>Отклонена</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Избранная модель</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" 
                                               {{ old('is_featured', $model->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label">Показывать на главной странице</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Управление фотографиями -->
                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="mb-0">Фотографии модели</h5>
                        </div>
                        <div class="content-card-body">
                            <!-- Форма загрузки фотографий -->
                            <div class="mb-4">
                                <div class="upload-zone" id="upload-zone">
                                    <div class="upload-zone-content">
                                        <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                                        <h5 class="mt-3">Перетащите фотографии сюда</h5>
                                        <p class="text-muted mb-3">или</p>
                                        <label for="photos-input" class="btn btn-primary mb-2">
                                            <i class="bi bi-folder2-open me-2"></i>Выбрать файлы
                                        </label>
                                        <input type="file" id="photos-input" name="photos[]" multiple accept="image/*" style="display: none;">
                                        <p class="text-muted small mb-0">
                                            Поддерживаются: JPG, PNG, GIF, BMP, TIFF, WebP<br>
                                            Максимальный размер файла: 20 МБ<br>
                                            Можно загружать до 50 файлов одновременно<br>
                                            <strong>Фотографии будут автоматически сжаты и конвертированы в WebP</strong>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Прогресс загрузки -->
                                <div id="upload-progress" class="mt-3" style="display: none;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span id="upload-status">Загрузка...</span>
                                        <span id="upload-percentage">0%</span>
                                    </div>
                                    <div class="progress">
                                        <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <!-- Превью выбранных файлов -->
                                <div id="preview-container" class="mt-3" style="display: none;">
                                    <h6>Выбранные файлы:</h6>
                                    <div id="preview-grid" class="row g-2"></div>
                                    <div class="mt-3">
                                        <button type="button" id="upload-button" class="btn btn-success">
                                            <i class="bi bi-upload me-2"></i>Загрузить фотографии
                                        </button>
                                        <button type="button" id="cancel-upload" class="btn btn-outline-secondary">
                                            Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Существующие фотографии -->
                            @if($model->photos && count($model->photos) > 0)
                            <div class="row g-3" id="photos-sortable">
                                @foreach($model->photos as $index => $photo)
                                <div class="col-md-3" data-photo-index="{{ $index }}">
                                    <div class="card position-relative">
                                        @if($index === 0)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-primary">Главная</span>
                                        </div>
                                        @endif
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: contain; background: #f8f9fa; cursor: move;"
                                             alt="Фото {{ $index + 1 }}">
                                        <div class="card-body p-2">
                                            <div class="btn-group w-100 mb-1">
                                                @if($index !== 0)
                                                <button type="button" class="btn btn-sm btn-outline-primary set-main-photo" 
                                                        data-index="{{ $index }}">
                                                    <i class="bi bi-star"></i> Главная
                                                </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-photo" 
                                                        data-index="{{ $index }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-success w-100 crop-photo" 
                                                    data-photo-path="{{ $photo }}"
                                                    data-photo-url="{{ asset('storage/' . $photo) }}">
                                                <i class="bi bi-crop"></i> Обрезать
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="photos_order" id="photos-order" value="">
                            <small class="text-muted d-block mt-3">
                                <i class="bi bi-info-circle"></i> Перетаскивайте фотографии для изменения порядка
                            </small>
                            @else
                            <p class="text-muted mb-0">Фотографии отсутствуют</p>
                            @endif
                        </div>
                    </div>
                </form>

<!-- Модальное окно для обрезки фотографий -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">
                    <i class="bi bi-crop me-2"></i>Обрезка фотографии
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="crop-container" style="max-height: 70vh; overflow: hidden;">
                            <img id="crop-image" src="" alt="Изображение для обрезки" style="max-width: 100%; display: block;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="crop-controls">
                            <h6 class="mb-3">Настройки обрезки</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small">Соотношение сторон</label>
                                <div class="alert alert-success small mb-2">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    <strong>Фиксированный формат: 2:3 (Портрет)</strong><br>
                                    Стандарт модельной индустрии для единообразия всех карточек
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Управление</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-2" id="crop-reset">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Сбросить
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-2" id="crop-zoom-in">
                                    <i class="bi bi-zoom-in me-1"></i>Приблизить
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="crop-zoom-out">
                                    <i class="bi bi-zoom-out me-1"></i>Отдалить
                                </button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Поворот</label>
                                <div class="btn-group w-100 mb-2" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="crop-rotate-left">
                                        <i class="bi bi-arrow-counterclockwise"></i> -90°
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="crop-rotate-right">
                                        <i class="bi bi-arrow-clockwise"></i> +90°
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Отражение</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="crop-flip-h">
                                        <i class="bi bi-symmetry-horizontal"></i> Гориз.
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="crop-flip-v">
                                        <i class="bi bi-symmetry-vertical"></i> Верт.
                                    </button>
                                </div>
                            </div>

                            <div class="alert alert-info small mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Выделите нужную область с помощью мыши. Можно перемещать и масштабировать рамку обрезки.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Отмена
                </button>
                <button type="button" class="btn btn-success" id="save-cropped-photo">
                    <i class="bi bi-check-circle me-1"></i>Сохранить обрезанное фото
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endpush

@push('scripts')
<!-- Cropper.js и Sortable.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photosContainer = document.getElementById('photos-sortable');
    const form = document.getElementById('model-edit-form');
    
    // Валидация обязательных полей перед отправкой формы
    form.addEventListener('submit', function(e) {
        const email = document.querySelector('input[name="email"]');
        const phone = document.querySelector('input[name="phone"]');
        const age = document.querySelector('input[name="age"]');
        let hasError = false;
        let errorMessages = [];
        
        // Проверяем email
        if (!email.value.trim()) {
            email.classList.add('is-invalid');
            hasError = true;
            errorMessages.push('Email');
        } else {
            email.classList.remove('is-invalid');
        }
        
        // Проверяем phone
        if (!phone.value.trim()) {
            phone.classList.add('is-invalid');
            hasError = true;
            errorMessages.push('Телефон');
        } else {
            phone.classList.remove('is-invalid');
        }
        
        // Проверяем age
        if (!age.value.trim()) {
            age.classList.add('is-invalid');
            hasError = true;
            errorMessages.push('Возраст');
        } else {
            age.classList.remove('is-invalid');
        }
        
        if (hasError) {
            e.preventDefault();
            showSaveIndicator('✗ Заполните обязательные поля: ' + errorMessages.join(', '), 'error');
            return false;
        }
    });
    let autoSaveTimeout = null;
    let isSaving = false;
    
    // Функция автосохранения
    function autoSave() {
        if (isSaving) {
            // Если уже идет сохранение, запланируем повторную попытку
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 300);
            return;
        }
        
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            const form = document.getElementById('model-edit-form');
            if (!form) {
                console.error('Форма редактирования не найдена');
                return;
            }
            const formData = new FormData(form);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            if (!csrfToken) {
                console.error('CSRF токен не найден');
                showSaveIndicator('Ошибка токена', 'error');
                return;
            }

            isSaving = true;
            showSaveIndicator('Сохранение...', 'loading');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Проверяем, есть ли контент
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Если не JSON, считаем успехом
                        return { success: true };
                    }
                } else if (response.status === 422) {
                    // Ошибка валидации
                    return response.json().then(data => {
                        const errors = Object.values(data.errors || {}).flat().join(', ');
                        throw new Error(errors || 'Ошибка валидации');
                    });
                } else if (response.status === 401 || response.status === 419) {
                    // Не авторизован или истекла сессия
                    window.location.reload(); // Перезагружаем страницу для повторной авторизации
                    throw new Error('Обновление страницы...');
                } else {
                    throw new Error('Ошибка: ' + response.status);
                }
            })
            .then(data => {
                isSaving = false;
                showSaveIndicator('Сохранено', 'success');
            })
            .catch(error => {
                isSaving = false;
                console.error('Ошибка автосохранения:', error);
                showSaveIndicator(error.message, 'error');
            });
        }, 500); // Ускоренная задержка 500мс после последнего изменения
    }
    
    // Улучшенный индикатор сохранения
    function showSaveIndicator(text, type = 'info') {
        let indicator = document.getElementById('autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autosave-indicator';
            indicator.style.cssText = `
                position: fixed; 
                top: 20px; 
                right: 20px; 
                padding: 12px 20px; 
                border-radius: 8px; 
                z-index: 9999; 
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 160px;
            `;
            document.body.appendChild(indicator);
        }
        
        let icon = '';
        let bgColor = '';
        let textColor = '';
        
        if (type === 'loading') {
            icon = '<span class="spinner-border spinner-border-sm" role="status"></span>';
            bgColor = '#0d6efd';
            textColor = '#ffffff';
        } else if (type === 'success') {
            icon = '<i class="bi bi-check-circle-fill"></i>';
            bgColor = '#198754';
            textColor = '#ffffff';
        } else if (type === 'error') {
            icon = '<i class="bi bi-exclamation-circle-fill"></i>';
            bgColor = '#dc3545';
            textColor = '#ffffff';
        } else {
            icon = '<i class="bi bi-info-circle-fill"></i>';
            bgColor = '#0dcaf0';
            textColor = '#000000';
        }
        
        indicator.innerHTML = icon + '<span>' + text + '</span>';
        indicator.style.backgroundColor = bgColor;
        indicator.style.color = textColor;
        indicator.style.opacity = '1';
        indicator.style.transform = 'translateY(0)';
        
        if (type === 'success') {
            setTimeout(() => {
                indicator.style.opacity = '0';
                indicator.style.transform = 'translateY(-10px)';
            }, 2000);
        } else if (type === 'error') {
            setTimeout(() => {
                indicator.style.opacity = '0';
                indicator.style.transform = 'translateY(-10px)';
            }, 4000);
        }
    }
    
    // Снятие класса is-invalid при вводе в обязательные поля
    const emailInput = document.querySelector('input[name="email"]');
    const ageInput = document.querySelector('input[name="age"]');
    
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }
    
    if (ageInput) {
        ageInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }
    
    // Автосохранение при изменении полей формы
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', autoSave);
        if (input.type === 'text' || input.tagName === 'TEXTAREA') {
            input.addEventListener('input', autoSave);
        }
    });
    
    if (photosContainer) {
        // Инициализация Sortable для перетаскивания
        new Sortable(photosContainer, {
            animation: 150,
            handle: 'img',
            ghostClass: 'opacity-50',
            dragClass: 'shadow-lg',
            onEnd: function() {
                updatePhotosOrder();
                updateBadges();
                // Используем небольшую задержку для операций с фотографиями
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSave();
                }, 300);
            }
        });
        
        // Установка главной фотографии
        photosContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('set-main-photo') || e.target.closest('.set-main-photo')) {
                e.preventDefault();
                const btn = e.target.classList.contains('set-main-photo') ? e.target : e.target.closest('.set-main-photo');
                const photoElement = btn.closest('.col-md-3');
                const photos = Array.from(photosContainer.children);
                
                // Перемещаем выбранную фотографию на первое место
                photosContainer.insertBefore(photoElement, photos[0]);
                updatePhotosOrder();
                updateBadges();
                
                // Сразу показываем индикатор и сохраняем
                showSaveIndicator('Сохранение главной фотографии...', 'loading');
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSave();
                }, 200); // Быстрое сохранение для важных операций
            }
        });
        
        // Удаление фотографии
        photosContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-photo') || e.target.closest('.delete-photo')) {
                e.preventDefault();
                const btn = e.target.classList.contains('delete-photo') ? e.target : e.target.closest('.delete-photo');
                
                if (confirm('Вы уверены, что хотите удалить эту фотографию?')) {
                    const photoElement = btn.closest('.col-md-3');
                    photoElement.remove();
                    updatePhotosOrder();
                    updateBadges();
                    
                    // Сразу сохраняем после удаления
                    showSaveIndicator('Сохранение...', 'loading');
                    clearTimeout(autoSaveTimeout);
                    autoSaveTimeout = setTimeout(() => {
                        autoSave();
                    }, 200);
                    
                    // Если удалили все фото, показываем сообщение
                    if (photosContainer.children.length === 0) {
                        photosContainer.innerHTML = '<div class="col-12"><p class="text-muted mb-0">Фотографии отсутствуют</p></div>';
                    }
                }
            }
        });
        
        // Обновление порядка фотографий
        function updatePhotosOrder() {
            const order = Array.from(photosContainer.children)
                .filter(el => el.querySelector('.card'))
                .map((el, idx) => {
                    const oldIndex = parseInt(el.dataset.photoIndex);
                    el.dataset.photoIndex = idx;
                    return oldIndex;
                });
            document.getElementById('photos-order').value = JSON.stringify(order);
        }
        
        // Обновление бейджей "Главная" и кнопок
        function updateBadges() {
            const photos = Array.from(photosContainer.children).filter(el => el.querySelector('.card'));
            
            photos.forEach((photo, idx) => {
                const card = photo.querySelector('.card');
                const btnGroup = card.querySelector('.btn-group');
                
                // Удаляем старый бейдж если есть
                const oldBadge = card.querySelector('.position-absolute.top-0.start-0.m-2');
                if (oldBadge) {
                    oldBadge.remove();
                }
                
                // Удаляем старую кнопку "Главная" если есть
                const oldSetMainBtn = btnGroup.querySelector('.set-main-photo');
                if (oldSetMainBtn) {
                    oldSetMainBtn.remove();
                }
                
                // Для первой фотографии добавляем бейдж "Главная"
                if (idx === 0) {
                    const badge = document.createElement('div');
                    badge.className = 'position-absolute top-0 start-0 m-2';
                    badge.innerHTML = '<span class="badge bg-primary">Главная</span>';
                    card.insertBefore(badge, card.firstChild);
                } else {
                    // Для остальных фото добавляем кнопку "Главная"
                    const deleteBtn = btnGroup.querySelector('.delete-photo');
                    const newBtn = document.createElement('button');
                    newBtn.type = 'button';
                    newBtn.className = 'btn btn-sm btn-outline-primary set-main-photo';
                    newBtn.dataset.index = idx;
                    newBtn.innerHTML = '<i class="bi bi-star"></i> Главная';
                    btnGroup.insertBefore(newBtn, deleteBtn);
                }
            });
        }
        
        // Инициализация при загрузке
        updatePhotosOrder();
    }
    
    // Строгая маска для телефона
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        // Снятие класса is-invalid при вводе
        phoneInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        // Автоматически добавляем +7 при фокусе на пустое поле
        phoneInput.addEventListener('focus', function(e) {
            if (!e.target.value) {
                e.target.value = '+7 (';
            }
        });
        
        // Очищаем при потере фокуса если только +7 (
        phoneInput.addEventListener('blur', function(e) {
            if (e.target.value === '+7 (' || e.target.value === '+7') {
                e.target.value = '';
            }
        });
        
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Если начинается с 8, меняем на 7
            if (value[0] === '8') {
                value = '7' + value.substring(1);
            }
            
            // Всегда начинаем с 7
            if (value[0] !== '7') {
                value = '7' + value;
            }
            
            // Ограничиваем длину
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            let formatted = '+7';
            
            if (value.length > 1) {
                formatted += ' (' + value.substring(1, 4);
            }
            if (value.length >= 5) {
                formatted += ') ' + value.substring(4, 7);
            }
            if (value.length >= 8) {
                formatted += '-' + value.substring(7, 9);
            }
            if (value.length >= 10) {
                formatted += '-' + value.substring(9, 11);
            }
            
            e.target.value = formatted;
        });
        
        // Запрещаем удалять +7 (
        phoneInput.addEventListener('keydown', function(e) {
            const cursorPosition = e.target.selectionStart;
            const value = e.target.value;
            
            // Запрещаем Backspace и Delete если курсор в начале (на +7 ()
            if ((e.key === 'Backspace' && cursorPosition <= 4) || 
                (e.key === 'Delete' && cursorPosition < 4)) {
                e.preventDefault();
            }
        });
    }

    // === МАССОВАЯ ЗАГРУЗКА ФОТОГРАФИЙ ===
    const uploadZone = document.getElementById('upload-zone');
    const photosInput = document.getElementById('photos-input');
    const previewContainer = document.getElementById('preview-container');
    const previewGrid = document.getElementById('preview-grid');
    const uploadButton = document.getElementById('upload-button');
    const cancelUploadBtn = document.getElementById('cancel-upload');
    const uploadProgress = document.getElementById('upload-progress');
    const uploadProgressBar = document.getElementById('upload-progress-bar');
    const uploadStatus = document.getElementById('upload-status');
    const uploadPercentage = document.getElementById('upload-percentage');
    const photosSortable = document.getElementById('photos-sortable');

    let selectedFiles = [];

    // Обработка клика по зоне загрузки
    uploadZone.addEventListener('click', function(e) {
        if (e.target === uploadZone || e.target.closest('.upload-zone-content')) {
            if (!e.target.closest('label')) {
                photosInput.click();
            }
        }
    });

    // Предотвращение стандартного поведения drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Подсветка зоны при наведении файла
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, function() {
            uploadZone.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, function() {
            uploadZone.classList.remove('drag-over');
        });
    });

    // Обработка drop
    uploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    });

    // Обработка выбора через input
    photosInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Обработка выбранных файлов
    function handleFiles(files) {
        selectedFiles = Array.from(files).filter(file => {
            // Проверяем тип файла
            return file.type.startsWith('image/');
        });

        if (selectedFiles.length === 0) {
            alert('Пожалуйста, выберите изображения');
            return;
        }

        if (selectedFiles.length > 50) {
            alert('Максимум 50 файлов за раз');
            selectedFiles = selectedFiles.slice(0, 50);
        }

        // Показываем превью
        displayPreview();
    }

    // Отображение превью
    function displayPreview() {
        previewGrid.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-2 col-sm-3 col-4';
                col.innerHTML = `
                    <div class="preview-item position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-preview" 
                                data-index="${index}" style="padding: 0.25rem 0.5rem;">
                            <i class="bi bi-x"></i>
                        </button>
                        <div class="text-center small mt-1 text-truncate" title="${file.name}">${file.name}</div>
                        <div class="text-center small text-muted">${(file.size / 1024 / 1024).toFixed(2)} МБ</div>
                    </div>
                `;
                previewGrid.appendChild(col);
            };
            
            reader.readAsDataURL(file);
        });

        previewContainer.style.display = 'block';
    }

    // Удаление файла из превью
    previewGrid.addEventListener('click', function(e) {
        if (e.target.closest('.remove-preview')) {
            const index = parseInt(e.target.closest('.remove-preview').dataset.index);
            selectedFiles.splice(index, 1);
            
            if (selectedFiles.length === 0) {
                previewContainer.style.display = 'none';
                photosInput.value = '';
            } else {
                displayPreview();
            }
        }
    });

    // Отмена загрузки
    cancelUploadBtn.addEventListener('click', function() {
        selectedFiles = [];
        previewContainer.style.display = 'none';
        photosInput.value = '';
    });

    // Загрузка фотографий
    uploadButton.addEventListener('click', function() {
        if (selectedFiles.length === 0) {
            alert('Выберите файлы для загрузки');
            return;
        }

        const formData = new FormData();
        selectedFiles.forEach(file => {
            formData.append('photos[]', file);
        });

        // Добавляем CSRF токен
        formData.append('_token', '{{ csrf_token() }}');

        // Показываем прогресс
        uploadProgress.style.display = 'block';
        uploadButton.disabled = true;
        cancelUploadBtn.disabled = true;
        uploadStatus.textContent = 'Загрузка и конвертация в WebP...';
        uploadPercentage.textContent = '0%';
        uploadProgressBar.style.width = '0%';

        // Отправляем AJAX запрос
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                uploadProgressBar.style.width = percentComplete + '%';
                uploadPercentage.textContent = percentComplete + '%';
            }
        });

        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    uploadStatus.textContent = response.message;
                    uploadProgressBar.classList.remove('progress-bar-animated');
                    uploadProgressBar.classList.add('bg-success');
                    
                    // Обновляем галерею фотографий
                    setTimeout(function() {
                        location.reload(); // Перезагружаем страницу для отображения новых фото
                    }, 1500);
                } else {
                    uploadStatus.textContent = 'Ошибка: ' + response.message;
                    uploadProgressBar.classList.remove('progress-bar-animated');
                    uploadProgressBar.classList.add('bg-danger');
                    uploadButton.disabled = false;
                    cancelUploadBtn.disabled = false;
                }
            } else {
                uploadStatus.textContent = 'Ошибка сервера: ' + xhr.status;
                uploadProgressBar.classList.remove('progress-bar-animated');
                uploadProgressBar.classList.add('bg-danger');
                uploadButton.disabled = false;
                cancelUploadBtn.disabled = false;
            }
        });

        xhr.addEventListener('error', function() {
            uploadStatus.textContent = 'Ошибка сети';
            uploadProgressBar.classList.remove('progress-bar-animated');
            uploadProgressBar.classList.add('bg-danger');
            uploadButton.disabled = false;
            cancelUploadBtn.disabled = false;
        });

        xhr.open('POST', '{{ route("admin.models.upload-photos", $model->id) }}');
        xhr.send(formData);
    });

    // === ОБРЕЗКА ФОТОГРАФИЙ С CROPPER.JS ===
    let cropper = null;
    let currentPhotoPath = null;
    const cropModal = document.getElementById('cropModal');
    const cropImage = document.getElementById('crop-image');
    const saveCroppedBtn = document.getElementById('save-cropped-photo');
    
    // Открытие модального окна для обрезки
    document.addEventListener('click', function(e) {
        if (e.target.closest('.crop-photo')) {
            const btn = e.target.closest('.crop-photo');
            currentPhotoPath = btn.dataset.photoPath;
            let photoUrl = btn.dataset.photoUrl;
            
            // Добавляем timestamp для обхода кеша при открытии модалки
            const url = new URL(photoUrl, window.location.origin);
            url.searchParams.set('v', Date.now());
            photoUrl = url.toString();
            
            console.log('Opening crop modal for:', photoUrl);
            
            // Устанавливаем изображение
            cropImage.src = photoUrl;
            
            // Показываем модалку
            const modal = new bootstrap.Modal(cropModal);
            modal.show();
        }
    });

    // Инициализация Cropper.js при открытии модалки
    cropModal.addEventListener('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(cropImage, {
            viewMode: 1,
            dragMode: 'move',
            aspectRatio: 2/3, // Фиксированное соотношение 2:3 (портрет) для модельных карточек
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            responsive: true,
            background: false,
            minContainerWidth: 200,
            minContainerHeight: 200
        });
    });

    // Уничтожение Cropper.js при закрытии модалки
    cropModal.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        currentPhotoPath = null;
    });

    // Кнопки управления соотношением сторон - ОТКЛЮЧЕНЫ (фиксированный формат 2:3)
    // Соотношение сторон заблокировано на 2:3 для единообразия карточек моделей

    // Кнопка сброса
    document.getElementById('crop-reset').addEventListener('click', function() {
        if (cropper) {
            cropper.reset();
        }
    });

    // Зум
    document.getElementById('crop-zoom-in').addEventListener('click', function() {
        if (cropper) {
            cropper.zoom(0.1);
        }
    });

    document.getElementById('crop-zoom-out').addEventListener('click', function() {
        if (cropper) {
            cropper.zoom(-0.1);
        }
    });

    // Поворот
    document.getElementById('crop-rotate-left').addEventListener('click', function() {
        if (cropper) {
            cropper.rotate(-90);
        }
    });

    document.getElementById('crop-rotate-right').addEventListener('click', function() {
        if (cropper) {
            cropper.rotate(90);
        }
    });

    // Отражение
    let scaleX = 1;
    let scaleY = 1;

    document.getElementById('crop-flip-h').addEventListener('click', function() {
        if (cropper) {
            scaleX = scaleX * -1;
            cropper.scaleX(scaleX);
        }
    });

    document.getElementById('crop-flip-v').addEventListener('click', function() {
        if (cropper) {
            scaleY = scaleY * -1;
            cropper.scaleY(scaleY);
        }
    });

    // Сохранение обрезанной фотографии
    saveCroppedBtn.addEventListener('click', function() {
        if (!cropper || !currentPhotoPath) {
            alert('Ошибка: нет данных для сохранения');
            return;
        }

        // Получаем данные обрезки
        const cropData = cropper.getData(true); // true = округляем значения

        // Показываем глобальный индикатор загрузки
        showSaveIndicator('Обрезка и сохранение фото...', 'loading');
        
        // Показываем индикатор на кнопке
        saveCroppedBtn.disabled = true;
        saveCroppedBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Сохранение...';

        // Отправляем AJAX запрос
        fetch('{{ route("admin.models.crop-photo", $model->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo_path: currentPhotoPath,
                crop_data: JSON.stringify(cropData)
            })
        })
        .then(response => {
            // Логируем полный ответ
            console.log('Crop response status:', response.status);
            
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Ошибка сервера');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Crop result:', data);
            
            if (data.success) {
                // Показываем успешное уведомление
                showSaveIndicator('Фото обрезано!', 'success');
                
                // Обновляем URL изображения с новым timestamp для всех вхождений
                const newPhotoUrl = data.photo_url;
                const timestamp = data.timestamp || Date.now();
                
                // Находим и обновляем все изображения с этим путем
                const photoElements = document.querySelectorAll('img');
                photoElements.forEach(img => {
                    const imgSrc = img.src.split('?')[0]; // Убираем параметры
                    const photoPathClean = currentPhotoPath.replace(/\\/g, '/');
                    
                    if (imgSrc.includes(photoPathClean)) {
                        console.log('Updating image:', img.src, '→', newPhotoUrl);
                        // Устанавливаем новый URL с timestamp
                        img.src = newPhotoUrl;
                        // Принудительно перезагружаем изображение
                        img.style.opacity = '0.5';
                        setTimeout(() => {
                            img.style.opacity = '1';
                        }, 100);
                    }
                });
                
                // Обновляем data-атрибуты кнопок обрезки
                const cropButtons = document.querySelectorAll('.crop-photo');
                cropButtons.forEach(btn => {
                    if (btn.dataset.photoPath === currentPhotoPath) {
                        btn.dataset.photoUrl = newPhotoUrl;
                    }
                });
                
                // Закрываем модалку
                bootstrap.Modal.getInstance(cropModal).hide();
                
                // Сбрасываем кнопку
                saveCroppedBtn.disabled = false;
                saveCroppedBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Сохранить обрезанное фото';
                
                // Автосохранение формы через 500мс
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSave();
                }, 500);
            } else {
                showSaveIndicator('Ошибка обрезки фото', 'error');
                alert('Ошибка: ' + data.message);
                saveCroppedBtn.disabled = false;
                saveCroppedBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Сохранить обрезанное фото';
            }
        })
        .catch(error => {
            console.error('Crop error:', error);
            showSaveIndicator('Ошибка сохранения', 'error');
            alert('Ошибка при сохранении фотографии: ' + error.message);
            saveCroppedBtn.disabled = false;
            saveCroppedBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Сохранить обрезанное фото';
        });
    });
});
</script>

<style>
.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    padding: 3rem 2rem;
    text-align: center;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-zone:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.upload-zone.drag-over {
    border-color: #0d6efd;
    background-color: #cfe2ff;
    border-style: solid;
}

.upload-zone-content {
    pointer-events: none;
}

.preview-item {
    margin-bottom: 1rem;
}

.preview-item img {
    border-radius: 0.375rem;
}

/* Стили для Cropper.js */
.crop-container {
    background: #000;
    border-radius: 0.375rem;
}

.aspect-ratio-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.crop-controls {
    max-height: 70vh;
    overflow-y: auto;
}
</style>
@endpush
