/**
 * Заглушки для функций - Этап 2 (Верстка)
 * Реальная функциональность будет разработана на этапе 3
 */

(function() {
    'use strict';

    // Конфигурация сообщений
    const messages = {
        default: 'Данная функция будет реализована на этапе 3 разработки',
        form: 'Отправка данных формы будет реализована на этапе 3',
        filter: 'Фильтрация моделей будет реализована на этапе 3',
        search: 'Поиск будет реализован на этапе 3',
        booking: 'Система бронирования будет реализована на этапе 3',
        casting: 'Обработка заявок на кастинг будет реализована на этапе 3',
        auth: 'Авторизация и регистрация будут реализованы на этапе 3',
        profile: 'Функции личного кабинета будут реализованы на этапе 3',
        admin: 'Функции админ-панели будут реализованы на этапе 3',
        upload: 'Загрузка файлов будет реализована на этапе 3',
        delete: 'Удаление будет реализовано на этапе 3',
        edit: 'Редактирование будет реализовано на этапе 3',
        save: 'Сохранение данных будет реализовано на этапе 3'
    };

    // Создание красивого модального уведомления
    function showStubNotification(message, type = 'info') {
        // Удаляем предыдущее уведомление, если есть
        const existing = document.getElementById('stub-notification');
        if (existing) {
            existing.remove();
        }

        // Создаем новое уведомление
        const notification = document.createElement('div');
        notification.id = 'stub-notification';
        notification.className = `stub-notification stub-${type}`;
        notification.innerHTML = `
            <div class="stub-notification-content">
                <div class="stub-notification-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="stub-notification-body">
                    <div class="stub-notification-title">Этап 2: Верстка завершена</div>
                    <div class="stub-notification-message">${message}</div>
                </div>
                <button class="stub-notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Автоматическое скрытие через 5 секунд
        setTimeout(() => {
            if (notification && notification.parentElement) {
                notification.classList.add('stub-notification-fade-out');
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }

    // Функция для перехвата отправки форм
    function interceptForms() {
        document.addEventListener('submit', function(e) {
            const form = e.target;
            
            // Пропускаем формы с классом stub-allow
            if (form.classList.contains('stub-allow')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            // Определяем тип формы
            let messageType = 'form';
            
            if (form.id === 'casting-form' || form.action.includes('casting')) {
                messageType = 'casting';
            } else if (form.action.includes('book') || form.classList.contains('booking-form')) {
                messageType = 'booking';
            } else if (form.action.includes('contact')) {
                messageType = 'form';
            } else if (form.action.includes('login') || form.action.includes('register')) {
                messageType = 'auth';
            } else if (form.action.includes('profile')) {
                messageType = 'profile';
            } else if (form.action.includes('admin')) {
                messageType = 'admin';
            }

            showStubNotification(messages[messageType], 'info');
            return false;
        }, true);
    }

    // Функция для перехвата кликов по кнопкам действий
    function interceptButtons() {
        document.addEventListener('click', function(e) {
            const button = e.target.closest('button, a');
            if (!button) return;

            // Пропускаем кнопки с классом stub-allow
            if (button.classList.contains('stub-allow')) {
                return;
            }

            // Перехватываем только кнопки с data-stub атрибутом
            if (button.hasAttribute('data-stub')) {
                e.preventDefault();
                e.stopPropagation();
                
                const stubType = button.getAttribute('data-stub') || 'default';
                const customMessage = button.getAttribute('data-stub-message');
                
                showStubNotification(
                    customMessage || messages[stubType] || messages.default,
                    'info'
                );
                return false;
            }

            // Автоматический перехват для определенных классов
            const autoInterceptClasses = [
                'btn-admin-action',
                'btn-upload',
                'btn-delete',
                'btn-edit',
                'btn-save',
                'btn-submit'
            ];

            for (const className of autoInterceptClasses) {
                if (button.classList.contains(className)) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    let messageType = 'default';
                    if (className.includes('upload')) messageType = 'upload';
                    else if (className.includes('delete')) messageType = 'delete';
                    else if (className.includes('edit')) messageType = 'edit';
                    else if (className.includes('save')) messageType = 'save';
                    else if (className.includes('admin')) messageType = 'admin';
                    
                    showStubNotification(messages[messageType], 'info');
                    return false;
                }
            }
        }, true);
    }

    // Функция для перехвата фильтров
    function interceptFilters() {
        // Перехват изменения фильтров
        const filterSelects = document.querySelectorAll('[data-filter-stub]');
        filterSelects.forEach(select => {
            select.addEventListener('change', function(e) {
                e.preventDefault();
                showStubNotification(messages.filter, 'info');
            });
        });

        // Перехват поиска
        const searchInputs = document.querySelectorAll('[data-search-stub]');
        searchInputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    showStubNotification(messages.search, 'info');
                }
            });
        });
    }

    // Инициализация при загрузке страницы
    function init() {
        // Небольшая задержка для корректной работы с динамическим контентом
        setTimeout(() => {
            interceptForms();
            interceptButtons();
            interceptFilters();
        }, 100);

        // Показываем уведомление о режиме демо при первой загрузке (опционально)
        const isFirstVisit = !sessionStorage.getItem('stub-notification-shown');
        if (isFirstVisit) {
            setTimeout(() => {
                showStubNotification(
                    'Вы просматриваете демонстрационную версию (Этап 2: Верстка). Функциональность будет добавлена на этапе 3.',
                    'info'
                );
                sessionStorage.setItem('stub-notification-shown', 'true');
            }, 1000);
        }
    }

    // Запуск при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Добавляем глобальную функцию для ручного вызова
    window.showStubNotification = showStubNotification;
    window.stubMessages = messages;

})();
