/**
 * Inline Editor - система редактирования контента на странице
 * 
 * Автоматически находит и делает редактируемыми все текстовые элементы на странице
 */

class InlineEditor {
    constructor() {
        this.isActive = false;
        this.activeElement = null;
        this.originalContent = null;
        this.toolbar = null;
        this.overlay = null;
        this.pageId = null;
        this.contentMap = {};
        
        this.init();
    }

    /**
     * Инициализация редактора
     */
    async init() {
        // Получаем ID страницы из DOM
        this.pageId = document.querySelector('[data-page-id]')?.dataset.pageId;
        
        if (!this.pageId) {
            console.error('Page ID not found');
            return;
        }

        this.createToolbar();
        this.createOverlay();
        
        // Загружаем сохраненный контент
        await this.loadSavedContent();
        
        // Делаем элементы редактируемыми
        this.makeElementsEditable();
        this.attachEventListeners();
        this.showWelcomeMessage();
    }

    /**
     * Загрузить сохраненный контент
     */
    async loadSavedContent() {
        try {
            const response = await fetch(`/api/inline-edit/content/${this.pageId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            
            if (data.success && data.content_map) {
                this.contentMap = data.content_map;
                this.applySavedContent();
            }
        } catch (error) {
            console.error('Error loading saved content:', error);
        }
    }

    /**
     * Применить сохраненный контент к элементам
     */
    applySavedContent() {
        Object.keys(this.contentMap).forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = this.contentMap[elementId];
            }
        });
    }

    /**
     * Создание панели инструментов
     */
    createToolbar() {
        this.toolbar = document.createElement('div');
        this.toolbar.id = 'inline-edit-toolbar';
        this.toolbar.innerHTML = `
            <div class="inline-toolbar-content">
                <div class="toolbar-left">
                    <span class="toolbar-icon">✏️</span>
                    <span class="toolbar-title">Режим редактирования</span>
                    <span class="toolbar-hint">Кликните на любой текст для редактирования</span>
                </div>
                <div class="toolbar-right">
                    <button id="inline-help-btn" class="toolbar-btn" title="Помощь">
                        <i class="bi bi-question-circle"></i>
                    </button>
                    <button id="inline-exit-btn" class="toolbar-btn toolbar-btn-exit" title="Выйти">
                        <i class="bi bi-x-lg"></i> Выйти
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(this.toolbar);
    }

    /**
     * Создание оверлея для подсветки элементов
     */
    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.id = 'inline-edit-overlay';
        document.body.appendChild(this.overlay);
    }

    /**
     * Показать приветственное сообщение
     */
    showWelcomeMessage() {
        const message = document.createElement('div');
        message.id = 'inline-welcome-message';
        message.innerHTML = `
            <div class="welcome-content">
                <h4>🎉 Режим редактирования активирован!</h4>
                <p>Кликните на любой текст на странице, чтобы его отредактировать.</p>
                <p class="text-muted small mb-0">Изменения сохраняются автоматически</p>
                <button id="close-welcome" class="btn-close-welcome">Понятно</button>
            </div>
        `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.classList.add('show');
        }, 100);

        document.getElementById('close-welcome').addEventListener('click', () => {
            message.classList.remove('show');
            setTimeout(() => message.remove(), 300);
        });

        // Автоматически скрыть через 5 секунд
        setTimeout(() => {
            if (message.parentNode) {
                message.classList.remove('show');
                setTimeout(() => message.remove(), 300);
            }
        }, 5000);
    }

    /**
     * Делает текстовые элементы редактируемыми
     */
    makeElementsEditable() {
        // Селекторы текстовых элементов, которые нужно сделать редактируемыми
        const selectors = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'span', 'a', 'li',
            '.lead', '.text-muted', '.hero-title',
            '.display-1', '.display-2', '.display-3', '.display-4', '.display-5', '.display-6'
        ];
        
        // Исключаем элементы внутри nav, footer, toolbar и других служебных областей
        const excludeParents = [
            '#inline-edit-toolbar',
            '#inline-edit-overlay', 
            '#inline-edit-panel',
            '#inline-welcome-message',
            'nav',
            'footer',
            'script',
            'style',
            'button',
            'input',
            'textarea',
            'select',
            '.hero-overlay',
            '.btn',
            '.navbar',
            'form'
        ];

        const elements = document.querySelectorAll(selectors.join(','));
        
        // Счетчики для каждого типа элемента
        const tagCounters = {};
        
        elements.forEach((element) => {
            // Проверяем, не находится ли элемент внутри исключенной области
            const isExcluded = excludeParents.some(selector => {
                return element.closest(selector) !== null;
            });
            
            if (isExcluded) return;
            
            // Проверяем, есть ли в элементе текст (не только пробелы)
            const hasText = element.textContent && element.textContent.trim().length > 0;
            
            // Проверяем, не содержит ли элемент другие редактируемые элементы
            const hasEditableChildren = Array.from(element.children).some(child => 
                selectors.some(sel => child.matches(sel))
            );
            
            if (!hasText || hasEditableChildren) return;
            
            // Генерируем уникальный ID если его нет (используем счетчик по типу тега)
            if (!element.id) {
                const tagName = element.tagName.toLowerCase();
                if (!tagCounters[tagName]) {
                    tagCounters[tagName] = 0;
                }
                element.id = `editable-${tagName}-${tagCounters[tagName]}`;
                tagCounters[tagName]++;
            }
            
            element.classList.add('inline-editable');
            element.setAttribute('data-original-content', element.textContent);
            
            // Добавляем обработчики
            element.addEventListener('mouseenter', (e) => {
                if (!this.activeElement) {
                    element.classList.add('inline-hover');
                    this.showOverlay(element);
                }
            });

            element.addEventListener('mouseleave', (e) => {
                if (!this.activeElement) {
                    element.classList.remove('inline-hover');
                    this.hideOverlay();
                }
            });

            element.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.startEditing(element);
            });
        });
    }

    /**
     * Показать оверлей вокруг элемента
     */
    showOverlay(element) {
        const rect = element.getBoundingClientRect();
        this.overlay.style.display = 'block';
        this.overlay.style.top = (rect.top + window.scrollY) + 'px';
        this.overlay.style.left = (rect.left + window.scrollX) + 'px';
        this.overlay.style.width = rect.width + 'px';
        this.overlay.style.height = rect.height + 'px';
    }

    /**
     * Скрыть оверлей
     */
    hideOverlay() {
        this.overlay.style.display = 'none';
    }

    /**
     * Начать редактирование элемента
     */
    startEditing(element) {
        if (this.activeElement) {
            this.cancelEditing();
        }

        this.activeElement = element;
        this.originalContent = element.textContent;

        // Делаем элемент редактируемым
        element.contentEditable = true;
        element.classList.add('inline-editing');
        element.focus();

        // Выделяем весь текст
        const range = document.createRange();
        range.selectNodeContents(element);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        // Создаем панель действий
        this.createEditPanel(element);

        // Скрываем оверлей во время редактирования
        this.hideOverlay();
    }

    /**
     * Создать панель действий для редактирования
     */
    createEditPanel(element) {
        const panel = document.createElement('div');
        panel.id = 'inline-edit-panel';
        panel.innerHTML = `
            <button id="inline-save-btn" class="edit-panel-btn edit-panel-save" title="Сохранить">
                <i class="bi bi-check-lg"></i> Сохранить
            </button>
            <button id="inline-cancel-btn" class="edit-panel-btn edit-panel-cancel" title="Отменить">
                <i class="bi bi-x-lg"></i> Отменить
            </button>
        `;

        document.body.appendChild(panel);

        // Позиционируем панель под элементом
        const rect = element.getBoundingClientRect();
        panel.style.top = (rect.bottom + window.scrollY + 10) + 'px';
        panel.style.left = (rect.left + window.scrollX) + 'px';

        // События для кнопок
        document.getElementById('inline-save-btn').addEventListener('click', () => {
            this.saveChanges(element);
        });

        document.getElementById('inline-cancel-btn').addEventListener('click', () => {
            this.cancelEditing();
        });

        // Сохранение по Ctrl+Enter
        element.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                this.saveChanges(element);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.cancelEditing();
            }
        });
    }

    /**
     * Сохранить изменения
     */
    async saveChanges(element) {
        const newContent = element.textContent.trim();
        
        if (newContent === this.originalContent) {
            this.cancelEditing();
            return;
        }

        if (!newContent) {
            alert('Содержимое не может быть пустым!');
            return;
        }

        // Показываем индикатор загрузки
        const saveBtn = document.getElementById('inline-save-btn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Сохранение...';

        try {
            console.log('Saving changes:', {
                pageId: this.pageId,
                elementId: element.id,
                oldContent: this.originalContent,
                newContent: newContent
            });
            
            const response = await fetch('/api/inline-edit/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    page_id: this.pageId,
                    element_id: element.id,
                    content: newContent
                })
            });

            const data = await response.json();
            
            console.log('Save response:', data);

            if (data.success) {
                // Обновляем локальную карту контента
                this.contentMap[element.id] = newContent;
                element.setAttribute('data-original-content', newContent);
                
                this.showNotification('✓ Изменения сохранены!', 'success');
                this.finishEditing();
            } else {
                throw new Error(data.error || 'Ошибка при сохранении');
            }
        } catch (error) {
            console.error('Save error:', error);
            this.showNotification('Ошибка при сохранении: ' + error.message, 'error');
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Сохранить';
        }
    }

    /**
     * Отменить редактирование
     */
    cancelEditing() {
        if (!this.activeElement) return;

        this.activeElement.textContent = this.originalContent;
        this.finishEditing();
    }

    /**
     * Завершить редактирование
     */
    finishEditing() {
        if (!this.activeElement) return;

        this.activeElement.contentEditable = false;
        this.activeElement.classList.remove('inline-editing', 'inline-hover');
        
        const panel = document.getElementById('inline-edit-panel');
        if (panel) panel.remove();

        this.activeElement = null;
        this.originalContent = null;
    }

    /**
     * Показать уведомление
     */
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `inline-notification inline-notification-${type}`;
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Присоединить обработчики событий
     */
    attachEventListeners() {
        // Кнопка выхода
        document.getElementById('inline-exit-btn').addEventListener('click', () => {
            if (this.activeElement) {
                if (confirm('У вас есть несохраненные изменения. Вы уверены, что хотите выйти?')) {
                    this.exit();
                }
            } else {
                this.exit();
            }
        });

        // Кнопка помощи
        document.getElementById('inline-help-btn').addEventListener('click', () => {
            this.showHelp();
        });

        // Отмена редактирования при клике вне элемента
        document.addEventListener('click', (e) => {
            if (this.activeElement && 
                !this.activeElement.contains(e.target) && 
                !document.getElementById('inline-edit-panel')?.contains(e.target)) {
                // Не отменяем автоматически - пользователь может случайно кликнуть
            }
        });
    }

    /**
     * Показать справку
     */
    showHelp() {
        const helpModal = document.createElement('div');
        helpModal.id = 'inline-help-modal';
        helpModal.innerHTML = `
            <div class="help-overlay" id="help-overlay"></div>
            <div class="help-content">
                <h3>Как использовать inline-редактор</h3>
                <ul>
                    <li>🖱️ <strong>Клик</strong> - наведите курсор на текст и кликните для редактирования</li>
                    <li>✏️ <strong>Редактирование</strong> - измените текст как вам нужно</li>
                    <li>💾 <strong>Сохранение</strong> - нажмите "Сохранить" или Ctrl+Enter</li>
                    <li>❌ <strong>Отмена</strong> - нажмите "Отменить" или Esc</li>
                    <li>🚪 <strong>Выход</strong> - нажмите "Выйти" в верхней панели</li>
                </ul>
                <button id="close-help" class="btn-close-help">Закрыть</button>
            </div>
        `;
        document.body.appendChild(helpModal);

        setTimeout(() => helpModal.classList.add('show'), 10);

        const closeHelp = () => {
            helpModal.classList.remove('show');
            setTimeout(() => helpModal.remove(), 300);
        };

        document.getElementById('close-help').addEventListener('click', closeHelp);
        document.getElementById('help-overlay').addEventListener('click', closeHelp);
    }

    /**
     * Выйти из режима редактирования
     */
    exit() {
        // Возвращаемся на страницу редактирования в админке
        const pageId = document.querySelector('[data-page-id]')?.dataset.pageId;
        if (pageId) {
            window.location.href = `/admin/pages/${pageId}/edit`;
        } else {
            window.location.href = '/admin/pages';
        }
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    // Проверяем, активирован ли режим inline-редактирования
    if (document.body.dataset.inlineEdit === 'true') {
        window.inlineEditor = new InlineEditor();
    }
});
