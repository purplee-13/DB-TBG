# Quick Start - Telegram Bot Setup

## 🚀 Setup Cepat (Windows)

### 1. Konfigurasi Environment
```bash
# Copy file .env.example ke .env
copy .env.example .env

# Edit .env dan tambahkan:
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 4. Start Laravel
```bash
php artisan serve
```

### 5. Setup Bot (Otomatis)
```bash
# Windows
scripts\setup-telegram-bot.bat

# Linux/Mac
./scripts/setup-telegram-bot.sh
```

## 🚀 Setup Cepat (Linux/Mac)

### 1. Konfigurasi Environment
```bash
# Copy file .env.example ke .env
cp .env.example .env

# Edit .env dan tambahkan:
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 4. Start Laravel
```bash
php artisan serve
```

### 5. Setup Bot (Otomatis)
```bash
# Linux/Mac
./scripts/setup-telegram-bot.sh
```

## 📱 Test Bot

Kirim perintah ke bot Telegram:
```
/update 1 Sudah Visit
/update 2 Progres
/update 3 Belum Visit
```

## 🔧 Manual Setup

Jika script otomatis tidak berfungsi:

### 1. Start ngrok
```bash
# Windows
scripts\start-ngrok.bat

# Linux/Mac
./scripts/start-ngrok.sh
```

### 2. Set Webhook
```bash
# Ganti URL dengan ngrok URL yang muncul
php artisan telegram:set-webhook https://abc123.ngrok-free.app/api/telegram/webhook
```

### 3. Cek Status
```bash
php artisan telegram:webhook-info
```

### 4. Test
```bash
php artisan telegram:test-webhook
```

## 📊 Monitoring

- **Logs**: `storage/logs/laravel.log`
- **ngrok UI**: http://localhost:4040
- **Webhook Status**: `php artisan telegram:webhook-info`

## 🆘 Troubleshooting

### Bot tidak merespons
1. Cek token bot di `.env`
2. Cek URL webhook: `php artisan telegram:webhook-info`
3. Cek logs: `tail -f storage/logs/laravel.log`

### ngrok tidak bisa diakses
1. Pastikan ngrok sudah running
2. Cek firewall/antivirus
3. Gunakan ngrok dengan authtoken

### CSRF Error
1. Pastikan route sudah dikecualikan di `VerifyCsrfToken.php`
2. Cek middleware di `routes/api.php`

## 📝 Format Perintah

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

## 🎯 Production

Untuk production, ganti ngrok dengan domain asli:
```bash
php artisan telegram:set-webhook https://yourdomain.com/api/telegram/webhook
```
