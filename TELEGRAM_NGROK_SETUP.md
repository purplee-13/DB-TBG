# Konfigurasi Bot Telegram dengan Ngrok

Panduan lengkap untuk menghubungkan bot Telegram dengan aplikasi Laravel menggunakan ngrok untuk development.

## 🚀 Quick Start

### 1. Install Ngrok
```bash
# Windows (dengan Chocolatey)
choco install ngrok

# Atau download manual dari https://ngrok.com/download
```

### 2. Setup Ngrok
```bash
# Daftar di https://ngrok.com dan dapatkan authtoken
ngrok config add-authtoken YOUR_AUTHTOKEN
```

### 3. Jalankan Laravel Server
```bash
php artisan serve --port=8000
```

### 4. Jalankan Ngrok
```bash
# Di terminal terpisah
ngrok http 8000
```

### 5. Set Webhook Telegram
```bash
# Copy URL ngrok (misal: https://abc123.ngrok.io)
php scripts/set-telegram-webhook.php https://abc123.ngrok.io
```

## 📋 Langkah Detail

### A. Persiapan Environment

1. **Pastikan file `.env` memiliki konfigurasi:**
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

2. **Install dependencies:**
```bash
composer install
```

### B. Konfigurasi Ngrok

1. **Download dan install ngrok:**
   - Download dari: https://ngrok.com/download
   - Extract ke folder yang mudah diakses
   - Tambahkan ke PATH environment

2. **Setup authtoken:**
```bash
ngrok config add-authtoken YOUR_AUTHTOKEN
```

### C. Menjalankan Aplikasi

1. **Start Laravel server:**
```bash
php artisan serve --port=8000
```

2. **Start ngrok (terminal terpisah):**
```bash
ngrok http 8000
```

3. **Copy URL ngrok yang muncul:**
```
https://abc123.ngrok.io
```

### D. Set Webhook Telegram

**Opsi 1: Menggunakan script PHP**
```bash
php scripts/set-telegram-webhook.php https://abc123.ngrok.io
```

**Opsi 2: Menggunakan PowerShell (Windows)**
```powershell
.\scripts\setup-telegram-ngrok.ps1 -NgrokUrl "https://abc123.ngrok.io"
```

**Opsi 3: Manual via browser/curl**
```
https://api.telegram.org/bot<BOT_TOKEN>/setWebhook?url=https://abc123.ngrok.io/api/telegram/webhook
```

## 🔧 Troubleshooting

### Error 419 (CSRF Token Mismatch)
- ✅ **Sudah diperbaiki** dengan menambahkan route ke `VerifyCsrfToken::$except`
- Routes yang dikecualikan: `telegram/webhook`, `api/telegram/webhook`

### Webhook tidak menerima pesan
1. **Cek log Laravel:**
```bash
tail -f storage/logs/laravel.log
```

2. **Test webhook info:**
```bash
curl https://api.telegram.org/bot<BOT_TOKEN>/getWebhookInfo
```

3. **Pastikan ngrok masih berjalan**

### Bot tidak merespon
1. **Cek format pesan:**
```
VISIT SITE123 oleh Andi, tanggal 2025-10-16
```

2. **Cek database connection**

3. **Cek TELEGRAM_BOT_TOKEN di .env**

## 📁 File yang Dibuat/Dimodifikasi

### Scripts Baru:
- `scripts/ngrok-setup.md` - Panduan setup ngrok
- `scripts/set-telegram-webhook.php` - Script PHP untuk set webhook
- `scripts/setup-telegram-ngrok.ps1` - Script PowerShell untuk Windows
- `scripts/start-ngrok.bat` - Batch file untuk start ngrok
- `scripts/start-ngrok.sh` - Shell script untuk Linux/Mac

### File yang Dimodifikasi:
- `app/Http/Controllers/TelegramController.php` - Ditambahkan logging dan error handling
- `app/Http/Middleware/VerifyCsrfToken.php` - Dikecualikan route webhook dari CSRF
- `routes/api.php` - Dibersihkan dan diperbaiki
- `routes/web.php` - Ditambahkan route webhook

## 🧪 Testing

1. **Kirim pesan ke bot dengan format:**
```
VISIT SITE123 oleh Andi, tanggal 2025-10-16
```

2. **Cek log untuk debugging:**
```bash
tail -f storage/logs/laravel.log
```

3. **Cek database apakah data terupdate**

## 📝 Catatan Penting

- **Ngrok URL berubah setiap restart** - Anda perlu set webhook ulang
- **Pastikan Laravel server berjalan** sebelum set webhook
- **Gunakan HTTPS** untuk webhook (ngrok otomatis menyediakan)
- **Log semua aktivitas** untuk debugging

## 🔄 Workflow Development

1. Start Laravel server: `php artisan serve --port=8000`
2. Start ngrok: `ngrok http 8000`
3. Copy URL ngrok
4. Set webhook: `php scripts/set-telegram-webhook.php <URL>`
5. Test dengan mengirim pesan ke bot
6. Cek log untuk debugging

## 🚨 Production Notes

Untuk production, ganti ngrok dengan:
- Domain yang sudah di-hosting
- SSL certificate yang valid
- Webhook URL yang permanen

Contoh production webhook:
```
https://yourdomain.com/api/telegram/webhook
```
