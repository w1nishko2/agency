@extends('layouts.admin')

@section('title', 'Редактирование страницы: ' . $page->title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Страницы</a></li>
    <li class="breadcrumb-item active">{{ $page->title }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-file-earmark-text me-2"></i>Редактирование страницы
    </h2>
    <div class="d-flex gap-2">
        @if($page->slug === '')
            <a href="{{ route('pages.inline-edit.home') }}" 
               class="btn btn-primary"
               target="_blank">
                <i class="bi bi-pencil-square me-1"></i>Редактировать текст на странице
            </a>
        @else
            <a href="{{ route('pages.inline-edit', $page->slug) }}" 
               class="btn btn-primary"
               target="_blank">
                <i class="bi bi-pencil-square me-1"></i>Редактировать текст на странице
            </a>
        @endif
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Назад к списку
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Ошибки при сохранении:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.pages.update', $page->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Левая колонка -->
        <div class="col-lg-8">
            
            <!-- Основная информация - скрыто -->
            <div class="content-card mb-4" style="display: none;">
                <div class="content-card-header">
                    <h5 class="mb-0">Основная информация</h5>
                </div>
                <div class="content-card-body">
                    
                    <!-- Заголовок -->
                    <div class="mb-4">
                        <label for="title" class="form-label">Заголовок страницы *</label>
                        <input type="text" 
                               class="form-control form-control-lg @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $page->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="8">{{ old('description', $page->description) }}</textarea>
                        <small class="text-muted">Основной текст страницы</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- SEO настройки -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-search me-2"></i>SEO настройки
                    </h5>
                </div>
                <div class="content-card-body">
                    
                    <!-- Meta Title -->
                    <div class="mb-4">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" 
                               class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" 
                               name="meta_title" 
                               value="{{ old('meta_title', $page->meta_title) }}" 
                               maxlength="255">
                        <small class="text-muted">Если не заполнено, будет использован заголовок страницы</small>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" 
                                  name="meta_description" 
                                  rows="3" 
                                  maxlength="255">{{ old('meta_description', $page->meta_description) }}</textarea>
                        <small class="text-muted">Описание для поисковых систем (до 255 символов)</small>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

        </div>

        <!-- Правая колонка -->
        <div class="col-lg-4">
            
            <!-- Главное изображение (Hero) -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-image me-2"></i>Главное изображение (Hero)
                    </h5>
                </div>
                <div class="content-card-body">
                    
                    @if($page->image)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $page->image) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $page->title }}"
                                 id="hero"
                                 style="max-height: 250px;">
                            <button type="button" 
                                    class="btn btn-sm btn-danger mt-2 w-100" 
                                    onclick="deleteImage('hero')">
                                <i class="bi bi-trash me-1"></i>Удалить
                            </button>
                        </div>
                    @else
                        <div class="text-center text-muted mb-3">
                            <i class="bi bi-image fs-1"></i>
                            <p class="small mb-0">Изображение не загружено</p>
                        </div>
                    @endif

                    <div class="mb-2">
                        <label for="image" class="form-label">Загрузить изображение</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="hero-input" 
                               name="image" 
                               accept="image/*"
                               onchange="previewImage(this, 'hero-preview')">
                        <small class="text-muted">Фон главного экрана. Автоматическая конвертация в WebP с оптимизацией</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary w-100" 
                            onclick="openCropModal(document.getElementById('hero-input'), 'hero')">
                        <i class="bi bi-crop me-1"></i>Обрезать изображение
                    </button>

                    <div id="hero-preview" class="mt-3" style="display: none;">
                        <img src="" class="img-fluid rounded" alt="Предпросмотр">
                    </div>

                </div>
            </div>

            <!-- Блок "О нас" - Изображения -->
            @if($page->slug === 'home')
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-images me-2"></i>Блок "О нас" - Карусель (4 фото)
                        </h5>
                    </div>
                    <div class="content-card-body">
                        
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $imageKey = "about_image_{$i}";
                                $currentImage = $page->images[$imageKey] ?? null;
                            @endphp
                            
                            <div class="mb-4 pb-3 border-bottom">
                                <h6 class="text-muted small mb-3">Фото {{ $i }}</h6>
                                
                                @if($currentImage)
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('storage/' . $currentImage) }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 150px;"
                                             alt="О нас {{ $i }}"
                                             id="about-image-{{ $i }}">
                                        <button type="button" 
                                                class="btn btn-sm btn-danger mt-2 w-100" 
                                                onclick="deleteImage('{{ $imageKey }}')">
                                            <i class="bi bi-trash me-1"></i>Удалить
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center text-muted mb-3">
                                        <i class="bi bi-image fs-3"></i>
                                        <p class="small mb-0">Не загружено</p>
                                    </div>
                                @endif

                                <input type="file" 
                                       class="form-control form-control-sm mb-2" 
                                       id="about-input-{{ $i }}"
                                       name="{{ $imageKey }}" 
                                       accept="image/*"
                                       onchange="previewImage(this, 'about-preview-{{ $i }}')">
                                
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary w-100" 
                                        onclick="openCropModal(document.getElementById('about-input-{{ $i }}'), '{{ $imageKey }}')">
                                    <i class="bi bi-crop me-1"></i>Обрезать
                                </button>
                                
                                <div id="about-preview-{{ $i }}" class="mt-2" style="display: none;">
                                    <img src="" class="img-fluid rounded" style="max-height: 150px;" alt="Предпросмотр">
                                </div>
                            </div>
                        @endfor
                        
                        <small class="text-muted">Изображения для карусели в блоке "О нас". Автоматическая конвертация в WebP с оптимизацией</small>

                    </div>
                </div>
            @elseif($page->slug === 'about')
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-image me-2"></i>Блок "История" - Фото
                        </h5>
                    </div>
                    <div class="content-card-body">
                        
                        @php
                            $imageKey = "about_image_1";
                            $currentImage = $page->images[$imageKey] ?? null;
                        @endphp
                        
                        @if($currentImage)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/' . $currentImage) }}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 200px;"
                                     alt="История"
                                     id="about-image-1">
                                <button type="button" 
                                        class="btn btn-sm btn-danger mt-2 w-100" 
                                        onclick="deleteImage('{{ $imageKey }}')">
                                    <i class="bi bi-trash me-1"></i>Удалить
                                </button>
                            </div>
                        @else
                            <div class="text-center text-muted mb-3">
                                <i class="bi bi-image fs-1"></i>
                                <p class="small mb-0">Изображение не загружено</p>
                            </div>
                        @endif

                        <div class="mb-2">
                            <label for="about-history-input" class="form-label">Загрузить изображение</label>
                            <input type="file" 
                                   class="form-control" 
                                   name="about_image_1" 
                                   id="about-history-input"
                                   accept="image/*"
                                   onchange="previewImage(this, 'about-preview-1')">
                            <small class="text-muted">Фото в блоке "Наша история". Автоматическая конвертация в WebP с оптимизацией</small>
                        </div>
                        
                        <button type="button" 
                                class="btn btn-sm btn-outline-primary w-100 mb-3" 
                                onclick="openCropModal(document.getElementById('about-history-input'), 'about_image_1')">
                            <i class="bi bi-crop me-1"></i>Обрезать изображение
                        </button>

                        <div id="about-preview-1" class="mt-3" style="display: none;">
                            <img src="" class="img-fluid rounded" alt="Предпросмотр">
                        </div>

                    </div>
                </div>
            @endif

            <!-- Публикация -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Настройки
                    </h5>
                </div>
                <div class="content-card-body">
                    
                    <div class="mb-3">
                        <label class="form-label">URL страницы</label>
                        <div class="input-group">
                            <span class="input-group-text">/</span>
                            <input type="text" class="form-control" value="{{ $page->slug }}" readonly>
                        </div>
                        <small class="text-muted">Slug изменить нельзя</small>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Страница активна
                        </label>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i>Сохранить изменения
                    </button>

                </div>
            </div>

        </div>
    </div>

</form>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endpush

@section('scripts')
<script>
let currentCropper = null;
let currentImageInput = null;
let currentImageType = null;

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteImage(imageType) {
    const messages = {
        'hero': 'главное изображение (Hero)',
        'about_image_1': 'фото 1 блока "О нас"',
        'about_image_2': 'фото 2 блока "О нас"',
        'about_image_3': 'фото 3 блока "О нас"',
        'about_image_4': 'фото 4 блока "О нас"'
    };
    
    if (!confirm(`Вы уверены, что хотите удалить ${messages[imageType] || 'изображение'}?`)) {
        return;
    }
    
    fetch('{{ route('admin.pages.delete-image', $page->id) }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ type: imageType })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        alert('Ошибка при удалении изображения');
        console.error(error);
    });
}

function openCropModal(input, imageType) {
    let imageUrl = null;
    
    // Проверяем, выбран ли новый файл
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            initCropperModal(e.target.result, imageType, input);
        };
        
        reader.readAsDataURL(file);
    } else {
        // Если файл не выбран, пытаемся загрузить существующее изображение
        const imageId = imageType.replace(/_/g, '-');
        const existingImage = document.getElementById(imageId);
        
        if (existingImage && existingImage.src) {
            initCropperModal(existingImage.src, imageType, input);
        } else {
            alert('Пожалуйста, сначала выберите изображение для загрузки');
            return;
        }
    }
}

function initCropperModal(imageSrc, imageType, inputElement) {
    currentImageInput = inputElement;
    currentImageType = imageType;
    
    const modal = new bootstrap.Modal(document.getElementById('cropModal'));
    const image = document.getElementById('cropImage');
    
    image.src = imageSrc;
    modal.show();
    
    // Инициализация cropper после открытия модалки
    document.getElementById('cropModal').addEventListener('shown.bs.modal', function initCropper() {
        if (currentCropper) {
            currentCropper.destroy();
        }
        
        currentCropper = new Cropper(image, {
            viewMode: 1,
            dragMode: 'move',
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            // Без ограничений по aspect ratio - свободная обрезка
        });
        
        // Удаляем обработчик после инициализации
        document.getElementById('cropModal').removeEventListener('shown.bs.modal', initCropper);
    });
}

function applyCrop() {
    if (!currentCropper) return;
    
    const canvas = currentCropper.getCroppedCanvas({
        maxWidth: 4096,
        maxHeight: 4096,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    canvas.toBlob(function(blob) {
        const formData = new FormData();
        formData.append('image', blob, 'cropped.jpg');
        formData.append('type', currentImageType);
        
        // Показываем индикатор загрузки
        const saveBtn = document.querySelector('#cropModal .btn-primary');
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Сохранение...';
        
        fetch('{{ route('admin.pages.crop-image', $page->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Закрываем модалку
                bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
                
                // Обновляем превью
                const imgId = currentImageType.replace(/_/g, '-');
                const img = document.getElementById(imgId);
                if (img) {
                    img.src = data.url + '?t=' + new Date().getTime();
                }
                
                // Очищаем input
                if (currentImageInput) {
                    currentImageInput.value = '';
                }
                
                // Показываем уведомление
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>Изображение успешно обрезано
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Вставляем уведомление в начало формы
                const form = document.querySelector('form');
                if (form) {
                    form.insertBefore(alert, form.firstChild);
                    setTimeout(() => alert.remove(), 3000);
                }
            } else {
                alert('Ошибка при сохранении: ' + (data.message || 'Неизвестная ошибка'));
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ошибка при сохранении изображения');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    }, 'image/jpeg', 0.95);
}

// Очистка при закрытии модалки
document.getElementById('cropModal')?.addEventListener('hidden.bs.modal', function() {
    if (currentCropper) {
        currentCropper.destroy();
        currentCropper = null;
    }
    currentImageInput = null;
    currentImageType = null;
});
</script>
@endsection

<!-- Модальное окно для обрезки -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-crop me-2"></i>Обрезка изображения
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div style="max-height: 70vh; overflow: hidden;">
                            <img id="cropImage" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Свободная обрезка:</strong> Вы можете обрезать изображение в любых пропорциях. 
                    Перетаскивайте и изменяйте размер области выделения как вам нужно.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Отмена
                </button>
                <button type="button" class="btn btn-primary" onclick="applyCrop()">
                    <i class="bi bi-check-circle me-1"></i>Применить обрезку
                </button>
            </div>
        </div>
    </div>
</div>
