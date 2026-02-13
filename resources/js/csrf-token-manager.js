/**
 * CSRF Token Manager - автоматическое обновление CSRF токена
 * 
 * Этот модуль предотвращает ошибку 419 (CSRF token mismatch),
 * автоматически обновляя токен каждые 1.5 часа (до истечения сессии)
 * и перехватывая ошибки 419 для повторной отправки запросов.
 */

class CsrfTokenManager {
    constructor() {
        this.tokenElement = null;
        this.refreshInterval = 90 * 60 * 1000; // 90 минут (1.5 часа)
        this.maxRetries = 3;
        this.retryDelay = 1000; // 1 секунда
        this.isRefreshing = false;
        this.failedRequests = [];
        
        this.init();
    }

    /**
     * Инициализация менеджера токенов
     */
    init() {
        // Находим meta тег с CSRF токеном
        this.tokenElement = document.querySelector('meta[name="csrf-token"]');
        
        if (!this.tokenElement) {
            console.error('CSRF token meta tag not found');
            return;
        }

        // Запускаем автоматическое обновление токена
        this.startAutoRefresh();
        
        // Перехватываем глобальные fetch запросы
        this.interceptFetch();
        
        // Перехватываем axios запросы (если используется)
        if (window.axios) {
            this.interceptAxios();
        }
        
        // Проверяем токен при активации вкладки
        this.handleVisibilityChange();
        
        console.log('✅ CSRF Token Manager инициализирован');
    }

    /**
     * Получить текущий токен
     */
    getToken() {
        return this.tokenElement ? this.tokenElement.getAttribute('content') : null;
    }

    /**
     * Обновить токен в DOM
     */
    setToken(token) {
        if (this.tokenElement) {
            this.tokenElement.setAttribute('content', token);
        }
        
        // Обновляем токен в axios headers (если используется)
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
        
        console.log('🔄 CSRF токен обновлен');
    }

    /**
     * Запросить новый токен с сервера
     */
    async refreshToken() {
        // Предотвращаем множественные одновременные запросы
        if (this.isRefreshing) {
            return this.waitForRefresh();
        }

        this.isRefreshing = true;

        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.token) {
                this.setToken(data.token);
                this.isRefreshing = false;
                this.processFailedRequests();
                return data.token;
            } else {
                throw new Error('Token not received from server');
            }
        } catch (error) {
            console.error('❌ Ошибка обновления CSRF токена:', error);
            this.isRefreshing = false;
            throw error;
        }
    }

    /**
     * Ожидать завершения текущего обновления
     */
    waitForRefresh() {
        return new Promise((resolve) => {
            const checkInterval = setInterval(() => {
                if (!this.isRefreshing) {
                    clearInterval(checkInterval);
                    resolve(this.getToken());
                }
            }, 100);
        });
    }

    /**
     * Обработать отложенные запросы после обновления токена
     */
    processFailedRequests() {
        this.failedRequests.forEach(({ resolve, reject, request }) => {
            request()
                .then(resolve)
                .catch(reject);
        });
        this.failedRequests = [];
    }

    /**
     * Добавить запрос в очередь на повтор
     */
    queueFailedRequest(request) {
        return new Promise((resolve, reject) => {
            this.failedRequests.push({ resolve, reject, request });
        });
    }

    /**
     * Запустить периодическое обновление токена
     */
    startAutoRefresh() {
        // Первое обновление через 90 минут
        setTimeout(() => {
            this.refreshToken().catch(err => {
                console.error('Ошибка автоматического обновления токена:', err);
            });
        }, this.refreshInterval);

        // Последующие обновления каждые 90 минут
        setInterval(() => {
            this.refreshToken().catch(err => {
                console.error('Ошибка автоматического обновления токена:', err);
            });
        }, this.refreshInterval);

        console.log(`⏰ Автообновление токена каждые ${this.refreshInterval / 60000} минут`);
    }

    /**
     * Перехватить нативные fetch запросы
     */
    interceptFetch() {
        const originalFetch = window.fetch;
        const self = this;

        window.fetch = async function(...args) {
            let [url, options = {}] = args;

            // Добавляем CSRF токен в заголовки
            if (!options.headers) {
                options.headers = {};
            }

            // Преобразуем Headers объект в обычный объект если нужно
            if (options.headers instanceof Headers) {
                const headersObj = {};
                options.headers.forEach((value, key) => {
                    headersObj[key] = value;
                });
                options.headers = headersObj;
            }

            // Добавляем токен для POST, PUT, PATCH, DELETE запросов
            const method = (options.method || 'GET').toUpperCase();
            if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                if (!options.headers['X-CSRF-TOKEN']) {
                    options.headers['X-CSRF-TOKEN'] = self.getToken();
                }
            }

            try {
                const response = await originalFetch(url, options);

                // Если получили 419 ошибку - обновляем токен и повторяем запрос
                if (response.status === 419) {
                    console.warn('⚠️ Ошибка 419: CSRF токен истек, обновляем...');
                    
                    await self.refreshToken();
                    
                    // Обновляем токен в заголовках
                    options.headers['X-CSRF-TOKEN'] = self.getToken();
                    
                    // Повторяем запрос
                    return originalFetch(url, options);
                }

                return response;
            } catch (error) {
                console.error('Ошибка fetch запроса:', error);
                throw error;
            }
        };
    }

    /**
     * Перехватить axios запросы
     */
    interceptAxios() {
        const self = this;

        // Request interceptor
        window.axios.interceptors.request.use(
            config => {
                // Добавляем свежий токен в каждый запрос
                const token = self.getToken();
                if (token) {
                    config.headers['X-CSRF-TOKEN'] = token;
                }
                return config;
            },
            error => {
                return Promise.reject(error);
            }
        );

        // Response interceptor для обработки 419 ошибок
        window.axios.interceptors.response.use(
            response => response,
            async error => {
                const originalRequest = error.config;

                // Если получили 419 ошибку и еще не пытались повторить
                if (error.response?.status === 419 && !originalRequest._retry) {
                    originalRequest._retry = true;

                    console.warn('⚠️ Ошибка 419: CSRF токен истек, обновляем...');

                    try {
                        // Обновляем токен
                        await self.refreshToken();
                        
                        // Обновляем токен в заголовках запроса
                        originalRequest.headers['X-CSRF-TOKEN'] = self.getToken();
                        
                        // Повторяем запрос
                        return window.axios(originalRequest);
                    } catch (refreshError) {
                        console.error('Не удалось обновить токен:', refreshError);
                        return Promise.reject(refreshError);
                    }
                }

                return Promise.reject(error);
            }
        );
    }

    /**
     * Обработка изменения видимости вкладки
     */
    handleVisibilityChange() {
        document.addEventListener('visibilitychange', async () => {
            if (!document.hidden) {
                // Когда пользователь возвращается на вкладку
                // Проверяем и обновляем токен если прошло много времени
                console.log('👀 Вкладка активна, проверяем токен...');
                
                try {
                    await this.refreshToken();
                } catch (error) {
                    console.error('Ошибка при проверке токена:', error);
                }
            }
        });
    }

    /**
     * Принудительно обновить токен (публичный метод)
     */
    async forceRefresh() {
        return await this.refreshToken();
    }
}

// Создаем глобальный экземпляр
window.csrfTokenManager = null;

// Инициализируем после загрузки DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.csrfTokenManager = new CsrfTokenManager();
    });
} else {
    window.csrfTokenManager = new CsrfTokenManager();
}

export default CsrfTokenManager;
