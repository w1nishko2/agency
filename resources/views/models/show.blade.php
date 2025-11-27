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
                    @if($model->photos && count($model->photos) > 0)
                        <img src="{{ asset('storage/' . $model->photos[0]) }}" 
                             alt="{{ $model->full_name }}" 
                             class="img-fluid mb-3"
                             style="aspect-ratio: 3/4; object-fit: cover;">
                    @else
                        <img src="{{ asset('imgsite/photo_4_2025-11-27_12-56-07.webp') }}" 
                             alt="{{ $model->full_name }}" 
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
                <div class="row g-3 mb-5">
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Возраст</div>
                            <div class="fw-bold">{{ $model->age }} {{ $model->age == 1 ? 'год' : ($model->age < 5 ? 'года' : 'лет') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Рост</div>
                            <div class="fw-bold">{{ $model->height }} см</div>
                        </div>
                    </div>
                    @if($model->bust && $model->waist && $model->hips)
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Грудь-Талия-Бедра</div>
                            <div class="fw-bold">{{ $model->bust }}-{{ $model->waist }}-{{ $model->hips }}</div>
                        </div>
                    </div>
                    @endif
                    @if($model->eye_color)
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Цвет глаз</div>
                            <div class="fw-bold">{{ ucfirst($model->eye_color) }}</div>
                        </div>
                    </div>
                    @endif
                    @if($model->hair_color)
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Цвет волос</div>
                            <div class="fw-bold">{{ ucfirst($model->hair_color) }}</div>
                        </div>
                    </div>
                    @endif
                    @if($model->shoe_size)
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light">
                            <div class="text-muted small text-uppercase mb-1">Размер обуви</div>
                            <div class="fw-bold">{{ $model->shoe_size }}</div>
                        </div>
                    </div>
                    @endif
                </div>

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
                        <div class="col-4">
                            <a href="{{ asset('storage/' . $photo) }}" 
                               data-lightbox="portfolio" 
                               data-title="{{ $model->full_name }}">
                                <img src="{{ asset('storage/' . $photo) }}" 
                                     class="img-fluid" 
                                     style="aspect-ratio: 3/4; object-fit: cover;"
                                     loading="lazy">
                            </a>
                        </div>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
