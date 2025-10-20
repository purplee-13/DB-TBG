# PowerShell script untuk setup Telegram webhook dengan ngrok
# Cara penggunaan: .\scripts\setup-telegram-ngrok.ps1

param(
    [string]$NgrokUrl = "",
    [int]$Port = 8000
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   TELEGRAM BOT NGROK SETUP" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek apakah ngrok sudah diinstall
try {
    $ngrokVersion = ngrok version 2>$null
    Write-Host "✅ Ngrok ditemukan: $ngrokVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Ngrok tidak ditemukan!" -ForegroundColor Red
    Write-Host "Download dari: https://ngrok.com/download" -ForegroundColor Yellow
    Write-Host "Atau install dengan: choco install ngrok" -ForegroundColor Yellow
    exit 1
}

# Cek apakah .env file ada
if (-not (Test-Path ".env")) {
    Write-Host "❌ File .env tidak ditemukan!" -ForegroundColor Red
    Write-Host "Pastikan file .env ada di root project" -ForegroundColor Yellow
    exit 1
}

# Cek TELEGRAM_BOT_TOKEN
$envContent = Get-Content ".env" -Raw
if ($envContent -notmatch "TELEGRAM_BOT_TOKEN=") {
    Write-Host "❌ TELEGRAM_BOT_TOKEN tidak ditemukan di .env!" -ForegroundColor Red
    Write-Host "Tambahkan: TELEGRAM_BOT_TOKEN=your_bot_token" -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ Konfigurasi .env OK" -ForegroundColor Green

# Jika URL ngrok tidak diberikan, jalankan ngrok
if ([string]::IsNullOrEmpty($NgrokUrl)) {
    Write-Host "🚀 Memulai ngrok untuk port $Port..." -ForegroundColor Yellow
    Write-Host "Setelah ngrok berjalan, copy URL yang muncul dan jalankan script ini lagi dengan parameter -NgrokUrl" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Contoh: .\scripts\setup-telegram-ngrok.ps1 -NgrokUrl 'https://delisa-unoutspoken-unempirically.ngrok-free.dev'" -ForegroundColor Gray
    Write-Host ""
    
    # Jalankan ngrok
    Start-Process -FilePath "ngrok" -ArgumentList "http", $Port -NoNewWindow
    exit 0
}

# Validasi URL ngrok
if ($NgrokUrl -notmatch "^https://.*\.ngrok\.(io|free\.dev)$") {
    Write-Host "❌ URL ngrok tidak valid!" -ForegroundColor Red
    Write-Host "Format yang benar: https://abc123.ngrok.io atau https://abc123.ngrok-free.dev" -ForegroundColor Yellow
    exit 1
}

Write-Host "🔗 Menggunakan URL ngrok: $NgrokUrl" -ForegroundColor Green

# Set webhook
$webhookUrl = "$NgrokUrl/telegram/webhook"
Write-Host "📡 Setting webhook: $webhookUrl" -ForegroundColor Cyan

# Load .env variables
$envVars = @{}
Get-Content ".env" | ForEach-Object {
    if ($_ -match "^([^=]+)=(.*)$") {
        $envVars[$matches[1]] = $matches[2]
    }
}

$botToken = $envVars["TELEGRAM_BOT_TOKEN"]
if ([string]::IsNullOrEmpty($botToken)) {
    Write-Host "❌ TELEGRAM_BOT_TOKEN tidak ditemukan!" -ForegroundColor Red
    exit 1
}

# Set webhook via API
$apiUrl = "https://api.telegram.org/bot$botToken/setWebhook"
$body = @{ url = $webhookUrl } | ConvertTo-Json

try {
    $response = Invoke-RestMethod -Uri $apiUrl -Method Post -Body $body -ContentType "application/json"
    
    if ($response.ok) {
        Write-Host "✅ Webhook berhasil diset!" -ForegroundColor Green
        Write-Host "📋 Response: $($response.description)" -ForegroundColor Cyan
        
        # Test webhook
        Write-Host ""
        Write-Host "🧪 Testing webhook..." -ForegroundColor Yellow
        $testUrl = "https://api.telegram.org/bot$botToken/getWebhookInfo"
        $testResponse = Invoke-RestMethod -Uri $testUrl
        
        if ($testResponse.ok) {
            Write-Host "✅ Webhook info:" -ForegroundColor Green
            Write-Host "   URL: $($testResponse.result.url)" -ForegroundColor Gray
            Write-Host "   Pending updates: $($testResponse.result.pending_update_count)" -ForegroundColor Gray
            Write-Host "   Last error: $($testResponse.result.last_error_message)" -ForegroundColor Gray
        }
    } else {
        Write-Host "❌ Error: $($response.description)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "📝 Catatan:" -ForegroundColor Cyan
Write-Host "- Pastikan ngrok masih berjalan" -ForegroundColor Gray
Write-Host "- Pastikan Laravel server berjalan di port $Port" -ForegroundColor Gray
Write-Host "- Test dengan mengirim pesan ke bot" -ForegroundColor Gray
