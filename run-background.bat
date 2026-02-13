@echo off
echo ================================================
echo Запуск Laravel в фоновом режиме
echo ================================================
echo.

:: Запуск планировщика Laravel (включает queue worker)
echo Запуск фонового процесса...
echo.

:: Запуск через VBScript для полностью скрытого окна
echo Set WshShell = CreateObject("WScript.Shell") > "%temp%\laravel-background.vbs"
echo WshShell.Run "cmd /c cd /d ""%~dp0"" && php artisan schedule:work", 0, False >> "%temp%\laravel-background.vbs"

cscript //nologo "%temp%\laravel-background.vbs"
del "%temp%\laravel-background.vbs"

echo.
echo ================================================
echo Процесс запущен в фоновом режиме!
echo ================================================
echo.
echo Планировщик Laravel работает незаметно в фоне
echo.
echo Для остановки используйте: stop-background.bat
echo Или завершите процесс php.exe в диспетчере задач
echo.
pause
