@extends('layouts.admin')

@section('title', 'Редактирование модели')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.index') }}">Модели</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.models.show', $model->id) }}">{{ $model->full_name }}</a></li>
    <li class="breadcrumb-item active">Редактирование</li>
@endsection

@section('content')

<h2 class="mb-4">Редактирование модели: {{ $model->full_name }}</h2>

<!-- Панель действий -->
<div class="content-card mb-4">
    <div class="content-card-body">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.models.show', $model->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>К профилю
            </a>
            <a href="{{ route('admin.models.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-list me-1"></i>К списку моделей
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.models.update', $model->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="mb-0">Основная информация</h5>
        </div>
        <div class="content-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Имя *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           name="first_name" value="{{ old('first_name', $model->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
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
                                           name="email" value="{{ old('email', $model->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Телефон *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone', $model->phone) }}" required>
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
                                           name="age" value="{{ old('age', $model->age) }}" min="16" max="100" required>
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
                                    <input type="text" class="form-control @error('clothing_size') is-invalid @enderror" 
                                           name="clothing_size" value="{{ old('clothing_size', $model->clothing_size) }}">
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
                            </div>
                        </div>
                    </div>

                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="mb-0">Дополнительная информация</h5>
                        </div>
                        <div class="content-card-body">
                            <div class="mb-3">
                                <label class="form-label">О себе</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          name="bio" rows="4">{{ old('bio', $model->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                           name="instagram" value="{{ old('instagram', $model->instagram) }}" placeholder="@username">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Telegram</label>
                                    <input type="text" class="form-control @error('telegram') is-invalid @enderror" 
                                           name="telegram" value="{{ old('telegram', $model->telegram) }}" placeholder="@username">
                                    @error('telegram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
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

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>Сохранить изменения
                        </button>
                        <a href="{{ route('admin.models.show', $model->id) }}" class="btn btn-outline-secondary btn-lg">
                            Отмена
                        </a>
                    </div>
                </form>

@endsection
