# Setup Telegram Bot dengan Laravel dan ngrok

## 1. Konfigurasi Environment

Tambahkan konfigurasi berikut ke file `.env`:

```env
# Telegram Bot Configuration
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

## 2. Install ngrok

### Windows:
1. Download ngrok dari https://ngrok.com/download
2. Extract dan tambahkan ke PATH
3. Atau gunakan script yang sudah disediakan di `scripts/start-ngrok.bat`

### Linux/Mac:
```bash
# Install via package manager atau download manual
# Gunakan script di scripts/start-ngrok.sh
```

## 3. Setup ngrok

### Manual:
```bash
# Start ngrok
ngrok http 8000

# Copy URL yang diberikan (contoh: https://abc123.ngrok-free.app)
```

### Menggunakan Script:
```bash
# Windows
scripts/start-ngrok.bat

# Linux/Mac
scripts/start-ngrok.sh
```

## 4. Set Webhook Telegram

### Menggunakan Artisan Command:
```bash
# Set webhook ke ngrok URL
php artisan telegram:set-webhook https://abc123.ngrok-free.app/api/telegram/webhook

# Cek status webhook
php artisan telegram:webhook-info
```

### Manual via API:
```bash
curl -X POST "https://api.telegram.org/bot<YOUR_BOT_TOKEN>/setWebhook" \
     -H "Content-Type: application/json" \
     -d '{"url": "https://abc123.ngrok-free.app/api/telegram/webhook"}'
```

## 5. Test Bot

Kirim perintah ke bot Telegram:
```
/update 1 Sudah Visit
/update 2 Progres
/update 3 Belum Visit
```

## 6. Monitoring

- Logs tersimpan di `storage/logs/laravel.log`
- Cek status webhook: `php artisan telegram:webhook-info`
- Test webhook: `php artisan telegram:test-webhook`

## 7. Troubleshooting

### Bot tidak merespons:
1. Cek token bot di `.env`
2. Cek URL webhook dengan `php artisan telegram:webhook-info`
3. Cek logs di `storage/logs/laravel.log`

### ngrok tidak bisa diakses:
1. Pastikan ngrok sudah running
2. Cek firewall/antivirus
3. Gunakan ngrok dengan authtoken (gratis)

### CSRF Error:
1. Pastikan route sudah dikecualikan di `VerifyCsrfToken.php`
2. Cek middleware di `routes/api.php`
