@echo off
echo ========================================
echo    SETUP TELEGRAM BOT LENGKAP
echo ========================================
echo.

echo 1. Cek konfigurasi environment...
if not exist ".env" (
    echo ❌ File .env tidak ditemukan!
    echo Silakan copy .env.example ke .env dan isi konfigurasi
    pause
    exit /b 1
)

echo ✅ File .env ditemukan
echo.

echo 2. Cek token bot...
findstr "TELEGRAM_BOT_TOKEN" .env >nul
if errorlevel 1 (
    echo ❌ TELEGRAM_BOT_TOKEN tidak ditemukan di .env!
    echo Silakan tambahkan TELEGRAM_BOT_TOKEN=your_token_here ke .env
    pause
    exit /b 1
)

echo ✅ Token bot ditemukan
echo.

echo 3. Start ngrok...
echo Memulai ngrok di background...
start /b ngrok http 8000
timeout /t 3 /nobreak >nul

echo ✅ ngrok sudah berjalan
echo.

echo 4. Cek status ngrok...
curl -s http://localhost:4040/api/tunnels > temp_ngrok.json 2>nul
if errorlevel 1 (
    echo ❌ Gagal mendapatkan info ngrok
    echo Pastikan ngrok sudah berjalan
    pause
    exit /b 1
)

echo ✅ ngrok berjalan
echo.

echo 5. Ekstrak URL ngrok...
for /f "tokens=*" %%i in ('powershell -command "Get-Content temp_ngrok.json | ConvertFrom-Json | Select-Object -ExpandProperty tunnels | Where-Object {$_.proto -eq 'https'} | Select-Object -ExpandProperty public_url"') do set NGROK_URL=%%i

if "%NGROK_URL%"=="" (
    echo ❌ Gagal mendapatkan URL ngrok
    echo Silakan cek manual di http://localhost:4040
    pause
    exit /b 1
)

echo ✅ URL ngrok: %NGROK_URL%
echo.

echo 6. Set webhook...
php artisan telegram:set-webhook %NGROK_URL%/api/telegram/webhook
if errorlevel 1 (
    echo ❌ Gagal set webhook
    pause
    exit /b 1
)

echo ✅ Webhook berhasil diatur
echo.

echo 7. Cek status webhook...
php artisan telegram:webhook-info
echo.

echo 8. Test webhook...
php artisan telegram:test-webhook
echo.

echo ========================================
echo    SETUP SELESAI!
echo ========================================
echo.
echo Bot siap digunakan!
echo URL webhook: %NGROK_URL%/api/telegram/webhook
echo.
echo Test dengan mengirim: /update 1 Sudah Visit
echo.

del temp_ngrok.json 2>nul
pause
