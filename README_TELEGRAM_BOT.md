# Konfigurasi Laravel untuk Bot Telegram dengan ngrok

## Overview
Sistem ini memungkinkan bot Telegram untuk mengupdate data maintenance site melalui perintah `/update <site_id> <status>`.

## Fitur
- ✅ Menerima perintah `/update <site_id> <status>` dari Telegram
- ✅ Update data site di database (progres dan tgl_visit)
- ✅ Kirim konfirmasi ke grup Telegram
- ✅ Kirim ringkasan total site yang sudah visit
- ✅ Logging lengkap untuk debugging
- ✅ Command artisan untuk setup webhook
- ✅ Support ngrok untuk development

## File yang Dibuat/Dimodifikasi

### 1. Controller
- `app/Http/Controllers/TelegramController.php` - Logika utama bot

### 2. Routes
- `routes/api.php` - Route webhook `/api/telegram/webhook`

### 3. Middleware
- `app/Http/Middleware/VerifyCsrfToken.php` - Exclude webhook dari CSRF

### 4. Commands
- `app/Console/Commands/SetTelegramWebhook.php` - Set webhook URL
- `app/Console/Commands/GetTelegramWebhookInfo.php` - Cek status webhook
- `app/Console/Commands/TestTelegramWebhook.php` - Test webhook

### 5. Documentation
- `TELEGRAM_SETUP.md` - Panduan setup lengkap
- `README_TELEGRAM_BOT.md` - Dokumentasi ini

## Setup Langkah demi Langkah

### 1. Konfigurasi Environment
Tambahkan ke `.env`:
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

### 2. Install ngrok
```bash
# Download dari https://ngrok.com/download
# Atau gunakan script yang sudah disediakan
```

### 3. Start ngrok
```bash
# Windows
scripts/start-ngrok.bat

# Linux/Mac
scripts/start-ngrok.sh
```

### 4. Set Webhook
```bash
# Set webhook ke ngrok URL
php artisan telegram:set-webhook https://abc123.ngrok-free.app/api/telegram/webhook

# Cek status webhook
php artisan telegram:webhook-info
```

### 5. Test Bot
Kirim perintah ke bot:
```
/update 1 Sudah Visit
/update 2 Progres
/update 3 Belum Visit
```

## Format Perintah Bot

### Update Site
```
/update <site_id> <status>
```

**Contoh:**
- `/update 1 Sudah Visit`
- `/update 2 Progres`
- `/update 3 Belum Visit`

### Response Bot
1. **Konfirmasi Update:**
   ```
   ✅ Site [nama_site] berhasil diperbarui menjadi [status] pada [tanggal sekarang].
   ```

2. **Ringkasan Total:**
   ```
   📊 Total site yang sudah visit: 12 dari 20 site.
   ```

## Database Schema

Tabel `sites` harus memiliki kolom:
- `id` (primary key)
- `nama_site` (string)
- `progres` (enum: "Belum Visit", "Sudah Visit", "Progres")
- `tgl_visit` (nullable datetime)
- `keterangan` (nullable text)

## Command Artisan

### Set Webhook
```bash
php artisan telegram:set-webhook https://your-ngrok-url.ngrok-free.app/api/telegram/webhook
```

### Cek Status Webhook
```bash
php artisan telegram:webhook-info
```

### Test Webhook
```bash
php artisan telegram:test-webhook
```

## Logging

Semua aktivitas bot dicatat di `storage/logs/laravel.log`:
- Request webhook dari Telegram
- Parsing perintah
- Update database
- Response ke Telegram
- Error handling

## Troubleshooting

### Bot tidak merespons
1. Cek token bot di `.env`
2. Cek URL webhook: `php artisan telegram:webhook-info`
3. Cek logs: `tail -f storage/logs/laravel.log`

### CSRF Error
1. Pastikan route sudah dikecualikan di `VerifyCsrfToken.php`
2. Cek middleware di `routes/api.php`

### ngrok tidak bisa diakses
1. Pastikan ngrok sudah running
2. Cek firewall/antivirus
3. Gunakan ngrok dengan authtoken

## Production Deployment

Untuk production, ganti ngrok dengan domain asli:
```bash
php artisan telegram:set-webhook https://yourdomain.com/api/telegram/webhook
```

## Security Notes

- Webhook route sudah dikecualikan dari CSRF
- Gunakan HTTPS untuk production
- Validasi input dari Telegram
- Rate limiting untuk mencegah spam
