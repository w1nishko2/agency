@echo off
echo ================================================
echo Остановка фоновых процессов Laravel
echo ================================================
echo.

:: Завершение всех процессов php artisan schedule:work и queue:work
taskkill /F /FI "WINDOWTITLE eq php artisan schedule:work*" 2>nul
taskkill /F /FI "WINDOWTITLE eq php artisan queue:work*" 2>nul

:: Дополнительная проверка процессов artisan
for /f "tokens=2" %%a in ('tasklist /FI "IMAGENAME eq php.exe" /FO LIST ^| findstr /I "PID"') do (
    wmic process where "ProcessId=%%a" get CommandLine 2>nul | findstr /I "artisan schedule:work" >nul && taskkill /F /PID %%a 2>nul
    wmic process where "ProcessId=%%a" get CommandLine 2>nul | findstr /I "artisan queue:work" >nul && taskkill /F /PID %%a 2>nul
)

echo.
echo Фоновые процессы остановлены!
echo.
pause
