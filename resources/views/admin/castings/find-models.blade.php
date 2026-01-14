@extends('layouts.admin')

@section('title', 'Подбор моделей по критериям')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.castings.index') }}">Кастинги</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.castings.show', $application->id) }}">#{{ $application->id }}</a></li>
    <li class="breadcrumb-item active">Подбор моделей</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Подбор моделей по критериям</h2>
        <p class="text-muted mb-0">Заявка #{{ $application->id }}: {{ $application->full_name }}</p>
    </div>
    <a href="{{ route('admin.castings.show', $application->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Вернуться к заявке
    </a>
</div>

<!-- Критерии поиска -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="mb-0"><i class="bi bi-filter me-2"></i>Критерии подбора</h5>
    </div>
    <div class="content-card-body">
        <div class="row g-3">
            @if($application->gender && $application->gender !== 'any')
            <div class="col-md-2">
                <div class="text-muted small">Пол</div>
                <div class="fw-semibold">{{ $application->gender == 'male' ? 'Мужской' : 'Женский' }}</div>
            </div>
            @endif
            
            @if($application->age && $application->age > 0)
            <div class="col-md-2">
                <div class="text-muted small">Возраст</div>
                <div class="fw-semibold">{{ $application->age - 3 }}-{{ $application->age + 3 }} лет</div>
            </div>
            @endif
            
            @if($application->height && $application->height > 0)
            <div class="col-md-2">
                <div class="text-muted small">Рост</div>
                <div class="fw-semibold">{{ $application->height - 5 }}-{{ $application->height + 5 }} см</div>
            </div>
            @endif
            
            @if($application->weight && $application->weight > 0)
            <div class="col-md-2">
                <div class="text-muted small">Вес</div>
                <div class="fw-semibold">{{ $application->weight - 5 }}-{{ $application->weight + 5 }} кг</div>
            </div>
            @endif
            
            @if($application->clothing_size && $application->clothing_size !== '-')
            <div class="col-md-2">
                <div class="text-muted small">Размер одежды</div>
                <div class="fw-semibold">{{ $application->clothing_size }}</div>
            </div>
            @endif
            
            @if($application->eye_color && $application->eye_color !== '-')
            <div class="col-md-2">
                <div class="text-muted small">Цвет глаз</div>
                <div class="fw-semibold">{{ $application->eye_color }}</div>
            </div>
            @endif
            
            @if($application->hair_color && $application->hair_color !== '-')
            <div class="col-md-2">
                <div class="text-muted small">Цвет волос</div>
                <div class="fw-semibold">{{ $application->hair_color }}</div>
            </div>
            @endif
            
            @if($application->has_experience)
            <div class="col-md-2">
                <div class="text-muted small">Опыт</div>
                <div class="fw-semibold"><i class="bi bi-check-circle text-success me-1"></i>Есть опыт</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Легенда совпадений -->
<div class="alert alert-info mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <strong>Процент совпадения:</strong>
        </div>
        <div class="col-auto">
            <span class="badge bg-success">80-100%</span> Отлично подходит
        </div>
        <div class="col-auto">
            <span class="badge bg-primary">60-79%</span> Хорошо подходит
        </div>
        <div class="col-auto">
            <span class="badge bg-warning">30-59%</span> Частично подходит
        </div>
    </div>
</div>

<!-- Результаты -->
<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-people me-2"></i>Найдено моделей
            <span class="badge bg-primary ms-2">{{ $models->total() }}</span>
        </h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                <i class="bi bi-check-square me-1"></i>Выбрать всех
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                <i class="bi bi-square me-1"></i>Снять выбор
            </button>
        </div>
    </div>
    <div class="content-card-body">
        @if($models->count() > 0)
            <form action="{{ route('admin.castings.assign-models', $application->id) }}" method="POST" id="assignModelsForm">
                @csrf
                
                <!-- Кнопка отправки -->
                <div class="alert alert-primary mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Выбрано моделей: <span id="selectedCount">0</span></strong>
                    </div>
                    <button type="submit" class="btn btn-primary" id="assignButton" disabled>
                        <i class="bi bi-check-lg me-1"></i>Записать выбранных на кастинг
                    </button>
                </div>
                
            <div class="row g-4">
                @foreach($models as $model)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-{{ $model->match_percent >= 80 ? 'success' : ($model->match_percent >= 60 ? 'primary' : 'secondary') }} model-card" data-model-id="{{ $model->id }}">
                        <!-- Чекбокс выбора -->
                        <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                            <div class="form-check">
                                <input class="form-check-input model-checkbox" 
                                       type="checkbox" 
                                       name="model_ids[]" 
                                       value="{{ $model->id }}" 
                                       id="model{{ $model->id }}"
                                       style="width: 24px; height: 24px;">
                            </div>
                        </div>
                        
                        <!-- Процент совпадения -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-{{ $model->match_percent >= 80 ? 'success' : ($model->match_percent >= 60 ? 'primary' : 'warning') }} fs-6">
                                {{ $model->match_percent }}% совпадение
                            </span>
                        </div>
                        
                        @if($model->main_photo && Storage::disk('public')->exists($model->main_photo))
                            <img src="{{ asset('storage/' . $model->main_photo) }}" 
                                 class="card-img-top" 
                                 style="height: 300px; object-fit: cover;"
                                 alt="{{ $model->full_name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 300px;">
                                <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-2">{{ $model->full_name }}</h5>
                            <div class="text-muted small mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ $model->city }}
                            </div>
                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                <span class="badge bg-secondary">{{ $model->age }} лет</span>
                                <span class="badge bg-secondary">{{ $model->height }} см</span>
                                @if($model->clothing_size)
                                <span class="badge bg-secondary">{{ $model->clothing_size }}</span>
                                @endif
                            </div>
                            <div class="small text-muted mb-3">
                                <div>Глаза: {{ $model->eye_color }}</div>
                                <div>Волосы: {{ $model->hair_color }}</div>
                                @if($model->experience_years > 0)
                                <div class="text-success"><i class="bi bi-star-fill me-1"></i>Опыт {{ $model->experience_years }} {{ $model->experience_years == 1 ? 'год' : ($model->experience_years < 5 ? 'года' : 'лет') }}</div>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.models.detail', $model->id) }}" 
                                   class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="bi bi-eye me-1"></i>Просмотр
                                </a>
                                <a href="{{ route('models.show', $model->id) }}" 
                                   class="btn btn-sm btn-outline-secondary"
                                   target="_blank"
                                   title="Открыть на сайте">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            </form>

            <!-- Пагинация -->
            @if($models->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $models->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 64px;"></i>
                <h4 class="mt-3 text-muted">Моделей не найдено</h4>
                <p class="text-muted">По указанным критериям не найдено ни одной модели</p>
                <a href="{{ route('admin.castings.show', $application->id) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i>Вернуться к заявке
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.model-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const selectedCountSpan = document.getElementById('selectedCount');
    const assignButton = document.getElementById('assignButton');
    const modelCards = document.querySelectorAll('.model-card');
    
    // Обновление счетчика и состояния кнопки
    function updateCount() {
        const checkedCount = document.querySelectorAll('.model-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        assignButton.disabled = checkedCount === 0;
        
        // Визуальное выделение выбранных карточек
        modelCards.forEach(card => {
            const checkbox = card.querySelector('.model-checkbox');
            if (checkbox.checked) {
                card.classList.add('border-3', 'shadow-lg');
            } else {
                card.classList.remove('border-3', 'shadow-lg');
            }
        });
    }
    
    // Выбрать всех
    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = true);
        updateCount();
    });
    
    // Снять выбор
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        updateCount();
    });
    
    // Отслеживание изменений чекбоксов
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
    
    // Клик по карточке (кроме кнопок) тоже переключает чекбокс
    modelCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Игнорируем клики по ссылкам и кнопкам
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            
            const checkbox = card.querySelector('.model-checkbox');
            checkbox.checked = !checkbox.checked;
            updateCount();
        });
    });
    
    // Подтверждение перед отправкой
    document.getElementById('assignModelsForm').addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.model-checkbox:checked').length;
        if (!confirm(`Записать ${count} ${count === 1 ? 'модель' : (count < 5 ? 'модели' : 'моделей')} на кастинг #{{ $application->id }}?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
