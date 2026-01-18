@echo off
REM Laravel Queue Worker with Scheduler
REM Этот скрипт запускает Laravel Scheduler для обработки очередей

cd /d "%~dp0"

echo [%date% %time%] Starting Laravel Scheduler...
php artisan schedule:run >> storage\logs\scheduler.log 2>&1

REM Этот файл должен запускаться через планировщик Windows каждую минуту
REM Инструкция по настройке:
REM 1. Откройте "Планировщик заданий Windows" (Task Scheduler)
REM 2. Создайте новое задание:
REM    - Имя: Laravel Scheduler
REM    - Триггер: Повторять каждую минуту
REM    - Действие: Запустить программу - путь к этому файлу
REM    - Настройки: Разрешить выполнение по требованию
