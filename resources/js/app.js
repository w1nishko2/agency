import './bootstrap';

// Инициализация Bootstrap компонентов
document.addEventListener('DOMContentLoaded', function() {
    
    // Инициализация всех tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Инициализация всех popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    
    // Плавная прокрутка для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#!') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Lazy loading изображений
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // Валидация форм
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Маска для телефона
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value[0] === '7' || value[0] === '8') {
                    value = '7' + value.substring(1);
                } else {
                    value = '7' + value;
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
                
                this.value = formatted;
            }
        });
    });
    
    // Превью изображений перед загрузкой
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = this.parentElement.querySelector('.preview-container');
            
            if (previewContainer && files.length > 0) {
                previewContainer.innerHTML = '';
                
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail me-2 mb-2';
                            img.style.width = '100px';
                            img.style.height = '100px';
                            img.style.objectFit = 'cover';
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    });
    
});

// Функция для AJAX фильтрации (каталог моделей)
window.filterModels = function() {
    const form = document.getElementById('filter-form');
    if (!form) return;
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    fetch('/models?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('.models-grid');
        const currentContent = document.querySelector('.models-grid');
        
        if (newContent && currentContent) {
            currentContent.innerHTML = newContent.innerHTML;
        }
    })
    .catch(error => console.error('Ошибка фильтрации:', error));
};

// Функция для отправки формы бронирования
window.submitBookingForm = function(modelId) {
    const form = document.getElementById('booking-form');
    if (!form) return;
    
    const formData = new FormData(form);
    
    fetch('/models/' + modelId + '/book', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            form.reset();
        } else {
            alert('Произошла ошибка. Попробуйте позже.');
        }
    })
    .catch(error => {
        console.error('Ошибка отправки:', error);
        alert('Произошла ошибка. Попробуйте позже.');
    });
};
