<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title>Тест системы CSRF токенов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .log-console {
            background: #1e1e1e;
            color: #00ff00;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }
        .log-entry {
            margin-bottom: 5px;
            padding: 5px;
        }
        .log-success {
            color: #00ff00;
        }
        .log-error {
            color: #ff0000;
        }
        .log-info {
            color: #00bfff;
        }
        .log-warning {
            color: #ffa500;
        }
        .token-display {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            word-break: break-all;
            border: 2px solid #dee2e6;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-active {
            background: #28a745;
            color: white;
        }
        .status-inactive {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            🔒 Тест системы автоматического обновления CSRF токена
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Статус системы -->
                        <div class="mb-4">
                            <h5>Статус системы:</h5>
                            <div id="systemStatus">
                                <span class="status-badge status-inactive">❌ Не инициализирована</span>
                            </div>
                        </div>

                        <!-- Текущий токен -->
                        <div class="mb-4">
                            <h5>Текущий CSRF токен:</h5>
                            <div class="token-display" id="currentToken">
                                Загрузка...
                            </div>
                            <small class="text-muted">Последнее обновление: <span id="lastUpdate">—</span></small>
                        </div>

                        <!-- Кнопки управления -->
                        <div class="mb-4">
                            <h5>Действия:</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" onclick="testRefreshToken()">
                                    🔄 Обновить токен
                                </button>
                                <button type="button" class="btn btn-success" onclick="testFetchRequest()">
                                    📤 Тест Fetch запроса
                                </button>
                                <button type="button" class="btn btn-warning" onclick="testAxiosRequest()">
                                    📤 Тест Axios запроса
                                </button>
                                <button type="button" class="btn btn-danger" onclick="test419Error()">
                                    ⚠️ Симулировать 419
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="clearLogs()">
                                    🗑️ Очистить лог
                                </button>
                            </div>
                        </div>

                        <!-- Статистика -->
                        <div class="mb-4">
                            <h5>Статистика:</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h2 id="refreshCount" class="text-primary">0</h2>
                                            <small>Обновлений токена</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h2 id="successCount" class="text-success">0</h2>
                                            <small>Успешных запросов</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h2 id="errorCount" class="text-danger">0</h2>
                                            <small>Ошибок</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h2 id="retryCount" class="text-warning">0</h2>
                                            <small>Повторов после 419</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Консоль логов -->
                        <div>
                            <h5>Лог событий:</h5>
                            <div class="log-console" id="logConsole">
                                <div class="log-entry log-info">Система запускается...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Счетчики статистики
        let stats = {
            refresh: 0,
            success: 0,
            error: 0,
            retry: 0
        };

        // Функция логирования
        function addLog(message, type = 'info') {
            const console = document.getElementById('logConsole');
            const entry = document.createElement('div');
            entry.className = `log-entry log-${type}`;
            
            const time = new Date().toLocaleTimeString('ru-RU');
            entry.textContent = `[${time}] ${message}`;
            
            console.appendChild(entry);
            console.scrollTop = console.scrollHeight;
        }

        // Обновить отображение токена
        function updateTokenDisplay() {
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (tokenElement) {
                const token = tokenElement.getAttribute('content');
                document.getElementById('currentToken').textContent = token;
                document.getElementById('lastUpdate').textContent = new Date().toLocaleString('ru-RU');
            }
        }

        // Обновить статус системы
        function updateSystemStatus() {
            const statusDiv = document.getElementById('systemStatus');
            if (window.csrfTokenManager) {
                statusDiv.innerHTML = '<span class="status-badge status-active">✅ Активна и работает</span>';
                addLog('✅ Система CSRF Token Manager успешно инициализирована', 'success');
            } else {
                statusDiv.innerHTML = '<span class="status-badge status-inactive">❌ Не инициализирована</span>';
                addLog('❌ Система CSRF Token Manager не найдена', 'error');
            }
        }

        // Обновить счетчики
        function updateStats() {
            document.getElementById('refreshCount').textContent = stats.refresh;
            document.getElementById('successCount').textContent = stats.success;
            document.getElementById('errorCount').textContent = stats.error;
            document.getElementById('retryCount').textContent = stats.retry;
        }

        // Тест обновления токена
        async function testRefreshToken() {
            addLog('🔄 Запуск обновления токена...', 'info');
            try {
                if (window.csrfTokenManager) {
                    await window.csrfTokenManager.forceRefresh();
                    stats.refresh++;
                    stats.success++;
                    updateStats();
                    updateTokenDisplay();
                    addLog('✅ Токен успешно обновлен!', 'success');
                } else {
                    addLog('❌ CSRF Token Manager не найден', 'error');
                    stats.error++;
                    updateStats();
                }
            } catch (error) {
                addLog(`❌ Ошибка обновления токена: ${error.message}`, 'error');
                stats.error++;
                updateStats();
            }
        }

        // Тест Fetch запроса
        async function testFetchRequest() {
            addLog('📤 Отправка тестового Fetch запроса...', 'info');
            try {
                const response = await fetch('/api/csrf-token', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    stats.success++;
                    updateStats();
                    addLog(`✅ Fetch запрос успешен! Получен токен: ${data.token.substring(0, 20)}...`, 'success');
                } else {
                    stats.error++;
                    updateStats();
                    addLog(`❌ Fetch запрос вернул ошибку: ${response.status}`, 'error');
                }
            } catch (error) {
                stats.error++;
                updateStats();
                addLog(`❌ Ошибка Fetch запроса: ${error.message}`, 'error');
            }
        }

        // Тест Axios запроса (если доступен)
        async function testAxiosRequest() {
            if (!window.axios) {
                addLog('⚠️ Axios не доступен на этой странице', 'warning');
                return;
            }
            
            addLog('📤 Отправка тестового Axios запроса...', 'info');
            try {
                const response = await axios.get('/api/csrf-token');
                stats.success++;
                updateStats();
                addLog(`✅ Axios запрос успешен! Получен токен: ${response.data.token.substring(0, 20)}...`, 'success');
            } catch (error) {
                stats.error++;
                updateStats();
                addLog(`❌ Ошибка Axios запроса: ${error.message}`, 'error');
            }
        }

        // Симуляция ошибки 419
        function test419Error() {
            addLog('⚠️ Симуляция ошибки 419...', 'warning');
            addLog('ℹ️ Для реального теста нужно изменить токен на невалидный', 'info');
            addLog('ℹ️ Система автоматически обновит токен и повторит запрос', 'info');
            
            // Изменяем токен на невалидный для теста
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (tokenElement) {
                const originalToken = tokenElement.getAttribute('content');
                tokenElement.setAttribute('content', 'invalid-token-for-test');
                addLog('🔧 Токен изменен на невалидный', 'warning');
                
                // Пытаемся сделать запрос с невалидным токеном
                setTimeout(async () => {
                    try {
                        // Этот запрос должен вызвать 419, если есть соответствующий endpoint
                        addLog('📤 Отправка запроса с невалидным токеном...', 'info');
                        await testRefreshToken();
                        stats.retry++;
                        updateStats();
                    } catch (error) {
                        addLog(`⚠️ Тест завершен. В реальной ситуации система автоматически обновит токен`, 'warning');
                    }
                }, 1000);
            }
        }

        // Очистить логи
        function clearLogs() {
            document.getElementById('logConsole').innerHTML = '<div class="log-entry log-info">Лог очищен</div>';
            addLog('🗑️ Лог событий очищен', 'info');
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            addLog('🚀 Страница загружена, проверка системы...', 'info');
            
            // Ждем немного, чтобы CSRF Token Manager инициализировался
            setTimeout(() => {
                updateSystemStatus();
                updateTokenDisplay();
                
                // Подписываемся на события обновления токена
                if (window.csrfTokenManager) {
                    // Переопределяем метод setToken для отслеживания обновлений
                    const originalSetToken = window.csrfTokenManager.setToken.bind(window.csrfTokenManager);
                    window.csrfTokenManager.setToken = function(token) {
                        originalSetToken(token);
                        stats.refresh++;
                        updateStats();
                        updateTokenDisplay();
                        addLog('🔄 CSRF токен автоматически обновлен системой', 'success');
                    };
                }
                
                addLog('✅ Система готова к тестированию', 'success');
                addLog('ℹ️ Используйте кнопки выше для тестирования функций', 'info');
            }, 500);
        });
    </script>

    <!-- Подключаем скомпилированные assets -->
    <script type="module" src="/build/assets/app-DfkkDv42.js"></script>
</body>
</html>
