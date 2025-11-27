@echo off
chcp 65001 > nul
echo.
echo ============================================
echo   ЗАПУСК ПРОЕКТА GOLDEN MODELS
echo ============================================
echo.

REM Проверка наличия .env
if not exist .env (
    echo [1/7] Создание файла .env...
    copy .env.example .env > nul
    echo ✓ Файл .env создан
) else (
    echo ✓ Файл .env уже существует
)

echo.
echo [2/7] Генерация ключа приложения...
php artisan key:generate

echo.
echo [3/7] Установка зависимостей Composer...
call composer install --no-interaction

echo.
echo [4/7] Установка зависимостей NPM...
call npm install

echo.
echo ============================================
echo   НАСТРОЙКА БАЗЫ ДАННЫХ
echo ============================================
echo.
echo Убедитесь, что:
echo 1. MySQL запущен в OSPanel
echo 2. Создана база данных "golden_models"
echo.
echo Для создания БД выполните в phpMyAdmin:
echo CREATE DATABASE golden_models CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
echo.
pause

echo.
echo [5/7] Запуск миграций...
php artisan migrate

echo.
echo [6/7] Загрузка тестовых данных...
php artisan db:seed --class=TestDataSeeder

echo.
echo [7/7] Создание символической ссылки для storage...
php artisan storage:link

echo.
echo ============================================
echo   СБОРКА FRONTEND
echo ============================================
echo.
echo Выберите режим сборки:
echo 1. Development (с hot reload)
echo 2. Production (минификация)
echo.
choice /c 12 /n /m "Ваш выбор (1 или 2): "

if errorlevel 2 (
    echo.
    echo Сборка для production...
    call npm run build
) else (
    echo.
    echo Запуск в режиме разработки...
    start cmd /k "npm run dev"
    timeout /t 3 > nul
)

echo.
echo ============================================
echo   ЗАПУСК СЕРВЕРА
echo ============================================
echo.
echo Сервер запускается на http://localhost:8000
echo.
echo Тестовые учетные данные:
echo.
echo Администратор:
echo   Email: admin@goldenmodels.ru
echo   Пароль: password
echo.
echo Модель:
echo   Email: anna@example.com
echo   Пароль: password
echo.
echo ============================================
echo.

php artisan serve

pause
