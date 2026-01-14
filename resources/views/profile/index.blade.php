@extends('layouts.main')

@section('title', 'Мой профиль - Golden Models')

@section('content')

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- Меню профиля -->
            <div class="col-lg-3">
                <div class="sticky-top" style="top: 100px;">
                    <h5 class="mb-3">Профиль</h5>
                    <div class="list-group">
                        <!-- Меню для модели -->
                        <a href="#overview" class="list-group-item list-group-item-action active" data-section="overview">
                            <i class="bi bi-person me-2"></i>Обзор
                        </a>
                        <a href="#edit" class="list-group-item list-group-item-action" data-section="edit">
                            <i class="bi bi-pencil me-2"></i>Редактировать
                        </a>
                        <a href="#photos" class="list-group-item list-group-item-action" data-section="photos">
                            <i class="bi bi-images me-2"></i>Фотографии
                        </a>
                        <a href="#settings" class="list-group-item list-group-item-action" data-section="settings">
                            <i class="bi bi-gear me-2"></i>Настройки
                        </a>
                        <a href="{{ route('logout') }}" 
                           class="list-group-item list-group-item-action text-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Выйти
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Содержимое -->
            <div class="col-lg-9">
                
                <!-- Обзор -->
                <div id="overview-section" class="profile-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Мой профиль</h2>
                        <span class="badge bg-warning text-dark">На модерации</span>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            @if($model && $model->photos && count($model->photos) > 0)
                                <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                                     class="img-fluid" 
                                     alt="Фото профиля">
                            @else
                                <img src="{{ asset('imgsite/photo_3_2025-11-27_12-56-07.webp') }}" 
                                     class="img-fluid" 
                                     alt="Фото профиля"
                                     style="aspect-ratio: 3/4; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $model ? $model->full_name : Auth::user()->name }}</h3>
                            @if($model)
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <small class="text-muted d-block">Возраст</small>
                                    <strong>{{ $model->age }} {{ $model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Рост</small>
                                    <strong>{{ $model->height }} см</strong>
                                </div>
                                @if($model->bust && $model->waist && $model->hips)
                                <div class="col-6">
                                    <small class="text-muted d-block">Параметры</small>
                                    <strong>{{ $model->bust }}-{{ $model->waist }}-{{ $model->hips }}</strong>
                                </div>
                                @endif
                                <div class="col-6">
                                    <small class="text-muted d-block">Город</small>
                                    <strong>{{ $model->city }}</strong>
                                </div>
                                @if($model->eye_color)
                                <div class="col-6">
                                    <small class="text-muted d-block">Цвет глаз</small>
                                    <strong>{{ ucfirst($model->eye_color) }}</strong>
                                </div>
                                @endif
                                @if($model->hair_color)
                                <div class="col-6">
                                    <small class="text-muted d-block">Цвет волос</small>
                                    <strong>{{ ucfirst($model->hair_color) }}</strong>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($model && $model->status == 'pending')
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Ваша анкета находится на модерации. Мы свяжемся с вами в течение 1-2 рабочих дней.
                            </div>
                            @elseif($model && $model->status == 'active')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Ваша анкета одобрена и опубликована!
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @else
                <!-- Обзор для модели -->
                <div id="overview-section" class="profile-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Мой профиль</h2>
                        @if($model && $model->status == 'pending')
                        <span class="badge bg-warning text-dark">На модерации</span>
                        @elseif($model && $model->status == 'active')
                        <span class="badge bg-success">Активен</span>
                        @elseif($model && $model->status == 'rejected')
                        <span class="badge bg-danger">Отклонен</span>
                        @endif
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            @if($model && $model->photos && count($model->photos) > 0)
                                <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                                     class="img-fluid" 
                                     alt="Фото профиля">
                            @else
                                <img src="{{ asset('imgsite/photo_3_2025-11-27_12-56-07.webp') }}" 
                                     class="img-fluid" 
                                     alt="Фото профиля"
                                     style="aspect-ratio: 3/4; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $model ? $model->full_name : Auth::user()->name }}</h3>
                            @if($model)
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <small class="text-muted d-block">Возраст</small>
                                    <strong>{{ $model->age }} {{ $model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Рост</small>
                                    <strong>{{ $model->height }} см</strong>
                                </div>
                                @if($model->bust && $model->waist && $model->hips)
                                <div class="col-6">
                                    <small class="text-muted d-block">Параметры</small>
                                    <strong>{{ $model->bust }}-{{ $model->waist }}-{{ $model->hips }}</strong>
                                </div>
                                @endif
                                <div class="col-6">
                                    <small class="text-muted d-block">Город</small>
                                    <strong>{{ $model->city }}</strong>
                                </div>
                                @if($model->eye_color)
                                <div class="col-6">
                                    <small class="text-muted d-block">Цвет глаз</small>
                                    <strong>{{ ucfirst($model->eye_color) }}</strong>
                                </div>
                                @endif
                                @if($model->hair_color)
                                <div class="col-6">
                                    <small class="text-muted d-block">Цвет волос</small>
                                    <strong>{{ ucfirst($model->hair_color) }}</strong>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($model && $model->status == 'pending')
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Ваша анкета находится на модерации. Мы свяжемся с вами в течение 1-2 рабочих дней.
                            </div>
                            @elseif($model && $model->status == 'active')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Ваша анкета одобрена и опубликована!
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Редактирование для модели -->
                <div id="edit-section" class="profile-section" style="display: none;">
                    <h2 class="mb-4">Редактировать профиль</h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Имя *</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                            </div>
                        </div>

                        @if($model)
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Возраст *</label>
                                <input type="number" class="form-control" name="age" value="{{ $model->age }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Рост (см) *</label>
                                <input type="number" class="form-control" name="height" value="{{ $model->height }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Вес (кг) *</label>
                                <input type="number" class="form-control" name="weight" value="{{ $model->weight }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Грудь (см)</label>
                                <input type="number" class="form-control" name="bust" value="{{ $model->bust }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Талия (см)</label>
                                <input type="number" class="form-control" name="waist" value="{{ $model->waist }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Бедра (см)</label>
                                <input type="number" class="form-control" name="hips" value="{{ $model->hips }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Цвет глаз *</label>
                                <select class="form-select" name="eye_color" required>
                                    <option value="Голубые" {{ $model->eye_color == 'голубые' ? 'selected' : '' }}>Голубые</option>
                                    <option value="Карие" {{ $model->eye_color == 'карие' ? 'selected' : '' }}>Карие</option>
                                    <option value="Зелёные" {{ $model->eye_color == 'зелёные' ? 'selected' : '' }}>Зелёные</option>
                                    <option value="Серые" {{ $model->eye_color == 'серые' ? 'selected' : '' }}>Серые</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Цвет волос *</label>
                                <select class="form-select" name="hair_color" required>
                                    <option value="Русый" {{ $model->hair_color == 'русый' ? 'selected' : '' }}>Русый</option>
                                    <option value="Блонд" {{ $model->hair_color == 'блонд' ? 'selected' : '' }}>Блонд</option>
                                    <option value="Каштановый" {{ $model->hair_color == 'каштановый' ? 'selected' : '' }}>Каштановый</option>
                                    <option value="Рыжий" {{ $model->hair_color == 'рыжий' ? 'selected' : '' }}>Рыжий</option>
                                    <option value="Чёрный" {{ $model->hair_color == 'чёрный' ? 'selected' : '' }}>Чёрный</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Город *</label>
                            <input type="text" class="form-control" name="city" value="{{ $model->city }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">О себе</label>
                            <textarea class="form-control" name="bio" rows="4">{{ $model->bio }}</textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Telegram</label>
                                <input type="text" class="form-control" name="telegram" placeholder="@username" value="{{ $model->telegram }}">
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>

                <!-- Фотографии -->
                <div id="photos-section" class="profile-section" style="display: none;">
                    <h2 class="mb-4">Мои фотографии</h2>
                    
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-upload me-2"></i>Загрузить фото
                        </button>
                    </div>

                    @if($model && $model->photos && count($model->photos) > 0)
                    <div class="row g-3">
                        @foreach($model->photos as $index => $photo)
                        <div class="col-md-4">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $photo) }}" 
                                     class="img-fluid" 
                                     style="aspect-ratio: 3/4; object-fit: cover;">
                                <form action="{{ route('profile.delete-photo', $index) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="return confirm('Удалить эту фотографию?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        У вас пока нет загруженных фотографий. Загрузите фото для создания портфолио.
                    </div>
                    @endif
                </div>

                <!-- Настройки -->
                <div id="settings-section" class="profile-section" style="display: none;">
                    <h2 class="mb-4">Настройки аккаунта</h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-3">Контактная информация</h5>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Телефон</label>
                            <input type="tel" class="form-control" name="phone" value="{{ $user->phone }}">
                        </div>

                        <h5 class="mb-3">Изменить пароль</h5>
                        <div class="mb-3">
                            <label class="form-label">Текущий пароль</label>
                            <input type="password" class="form-control" name="current_password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Подтвердите новый пароль</label>
                            <input type="password" class="form-control" name="new_password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить настройки</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Модальное окно загрузки фото -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Загрузить фотографии</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.upload-photos') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Выберите фотографии</label>
                        <input type="file" class="form-control" name="photos[]" multiple accept="image/*" required>
                        <small class="text-muted">Максимум 10 фото, до 5 МБ каждое</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Загрузить</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('[data-section]');
    const sections = document.querySelectorAll('.profile-section');
    
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Убираем активный класс со всех ссылок
            menuLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Скрываем все секции
            sections.forEach(s => s.style.display = 'none');
            
            // Показываем нужную секцию
            const sectionId = this.dataset.section + '-section';
            document.getElementById(sectionId).style.display = 'block';
        });
    });
});
</script>
@endpush
