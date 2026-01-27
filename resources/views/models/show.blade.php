@extends('layouts.main')

@section('title', $model->full_name . ' - Golden Models')
@section('description', 'Профессиональная модель ' . $model->full_name . '. Возраст: ' . $model->age . ' ' . ($model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет')) . ', рост: ' . $model->height . ' см.')

@section('content')

<!-- Профиль модели -->
<section class="py-5">
    <div class="container">
        <div class="row">
            
            <!-- Основное фото -->
            <div class="col-lg-5 mb-4">
                <div class="sticky-top" style="top: 100px;">
                    @if($model->photos && count($model->photos) > 0 && Storage::disk('public')->exists($model->photos[0]))
                        <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                             alt="{{ e($model->full_name) }}" 
                             class="img-fluid mb-3"
                             style="aspect-ratio: 3/4; object-fit: cover;">
                    @else
                        <img src="{{ asset('imgsite/placeholder.svg') }}" 
                             alt="Фото отсутствует"
                             class="img-fluid mb-3"
                             style="aspect-ratio: 3/4; object-fit: cover;">
                    @endif
                    
                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-envelope me-2"></i>Пригласить модель
                    </button>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark flex-fill">
                            <i class="bi bi-heart"></i>
                        </button>
                        <button class="btn btn-outline-dark flex-fill">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Информация -->
            <div class="col-lg-7">
                <div class="mb-4">
                    <h1 class="mb-2">{{ strtoupper($model->full_name) }}</h1>
                    <p class="text-muted">
                        <i class="bi bi-geo-alt me-2"></i>{{ $model->city }}, Россия
                    </p>
                </div>

                <!-- Основные параметры -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Параметры</h5>
                        
                        <div class="row g-4">
                            <!-- Возраст -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Возраст</div>
                                        <div class="fw-bold">{{ $model->age }} {{ $model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Пол -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gender-{{ $model->gender == 'male' ? 'male' : 'female' }} text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Пол</div>
                                        <div class="fw-bold">{{ $model->gender == 'male' ? 'Мужской' : 'Женский' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Рост -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrows-vertical text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Рост</div>
                                        <div class="fw-bold">{{ $model->height }} см</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Вес -->
                            @if($model->weight)
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-speedometer2 text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Вес</div>
                                        <div class="fw-bold">{{ $model->weight }} кг</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Параметры фигуры -->
                            @if($model->bust && $model->waist && $model->hips)
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="text-muted small mb-2">Параметры фигуры</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-center">
                                            <div class="text-muted small">Грудь</div>
                                            <div class="fw-bold fs-5">{{ $model->bust }}</div>
                                        </div>
                                        <div class="text-muted">×</div>
                                        <div class="text-center">
                                            <div class="text-muted small">Талия</div>
                                            <div class="fw-bold fs-5">{{ $model->waist }}</div>
                                        </div>
                                        <div class="text-muted">×</div>
                                        <div class="text-center">
                                            <div class="text-muted small">Бедра</div>
                                            <div class="fw-bold fs-5">{{ $model->hips }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Размер одежды -->
                            @if($model->clothing_size)
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-tags text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Размер одежды</div>
                                        <div class="fw-bold">{{ $model->clothing_size }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Размер обуви -->
                            @if($model->shoe_size)
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-diagram-3 text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Размер обуви</div>
                                        <div class="fw-bold">{{ $model->shoe_size }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Внешность -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Внешность</h5>
                        
                        <div class="row g-4">
                            <!-- Цвет глаз -->
                            @if($model->eye_color)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-eye text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Цвет глаз</div>
                                        <div class="fw-bold">{{ ucfirst($model->eye_color) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Цвет волос -->
                            @if($model->hair_color)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-brush text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Цвет волос</div>
                                        <div class="fw-bold">{{ ucfirst($model->hair_color) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Тип внешности -->
                            @if($model->appearance_type)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-badge text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Тип внешности</div>
                                        <div class="fw-bold">{{ ucfirst($model->appearance_type) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Цвет кожи -->
                            @if($model->skin_color)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-palette text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Цвет кожи</div>
                                        <div class="fw-bold">{{ ucfirst($model->skin_color) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Татуировки -->
                            @if($model->has_tattoos !== null)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $model->has_tattoos ? 'check-circle-fill text-success' : 'x-circle text-muted' }} me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Татуировки</div>
                                        <div class="fw-bold">{{ $model->has_tattoos ? 'Есть' : 'Нет' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Пирсинг -->
                            @if($model->has_piercings !== null)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $model->has_piercings ? 'check-circle-fill text-success' : 'x-circle text-muted' }} me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Пирсинг</div>
                                        <div class="fw-bold">{{ $model->has_piercings ? 'Есть' : 'Нет' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Дополнительная информация -->
                @if($model->languages || $model->skills || $model->has_modeling_school || $model->experience_years)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Дополнительно</h5>
                        
                        <div class="row g-4">
                            <!-- Языки -->
                            @if(is_array($model->languages) && count($model->languages) > 0)
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-translate text-primary me-2 fs-4"></i>
                                    <div class="flex-fill">
                                        <div class="text-muted small mb-2">Языки</div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach($model->languages as $lang)
                                                <span class="badge bg-light text-dark border">{{ $lang }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Навыки -->
                            @if(is_array($model->skills) && count($model->skills) > 0)
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-star text-primary me-2 fs-4"></i>
                                    <div class="flex-fill">
                                        <div class="text-muted small mb-2">Навыки</div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach($model->skills as $skill)
                                                <span class="badge bg-primary">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Опыт -->
                            @if($model->experience_years)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-award text-primary me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Опыт работы</div>
                                        <div class="fw-bold">{{ $model->experience_years }} {{ $model->experience_years == 1 ? 'год' : ($model->experience_years < 5 ? 'года' : 'лет') }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Модельная школа -->
                            @if($model->has_modeling_school !== null)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $model->has_modeling_school ? 'mortarboard-fill text-success' : 'mortarboard text-muted' }} me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Модельная школа</div>
                                        <div class="fw-bold">{{ $model->has_modeling_school ? 'Окончена' : 'Не окончена' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Профессиональный опыт -->
                            @if($model->has_professional_experience !== null)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $model->has_professional_experience ? 'briefcase-fill text-success' : 'briefcase text-muted' }} me-2 fs-4"></i>
                                    <div>
                                        <div class="text-muted small">Проф. опыт</div>
                                        <div class="fw-bold">{{ $model->has_professional_experience ? 'Есть' : 'Нет' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($model->bio)
                <!-- О модели -->
                <div class="mb-5">
                    <h3 class="mb-3">О модели</h3>
                    <p class="text-muted">{{ $model->bio }}</p>
                </div>
                @endif

                @if($model->category)
                <!-- Категории -->
                <div class="mb-5">
                    <h3 class="mb-3">Категория</h3>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-dark">{{ ucfirst($model->category) }}</span>
                    </div>
                </div>
                @endif

                <!-- Портфолио -->
                @if($model->photos && count($model->photos) > 1)
                <div class="mb-5">
                    <h3 class="mb-3">Портфолио</h3>
                    <div class="row g-3">
                        @foreach(array_slice($model->photos, 1, 6) as $photo)
                            @if(Storage::disk('public')->exists($photo))
                            <div class="col-4">
                                <a href="{{ asset('storage/' . $photo) }}" 
                                   data-lightbox="portfolio" 
                                   data-title="{{ e($model->full_name) }}">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         class="img-fluid" 
                                         style="aspect-ratio: 3/4; object-fit: cover;"
                                         loading="lazy">
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

<!-- Модальное окно приглашения -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Пригласить модель</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('models.book', $model->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Ваше имя *</label>
                        <input type="text" class="form-control" name="client_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Телефон *</label>
                        <input type="tel" class="form-control" name="client_phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="client_email">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Дата мероприятия</label>
                        <input type="date" class="form-control" name="event_date">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Комментарий</label>
                        <textarea class="form-control" name="message" rows="4" 
                                  placeholder="Расскажите о проекте"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Отправить заявку</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" 
      integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" 
        integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>
@endpush
