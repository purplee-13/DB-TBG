# Setup Telegram Bot

## 📋 Cara Setup Bot Telegram

### 1. Buat Bot di Telegram
1. Buka Telegram dan cari **@BotFather**
2. Kirim command `/newbot`
3. Ikuti instruksi untuk memberikan nama bot
4. Setelah selesai, BotFather akan memberikan **token bot** (format: `123456789:ABCdefGHIjklMNOpqrsTUVwxyz`)
5. **Simpan token ini!**

### 2. Konfigurasi Bot
1. Buka file `public/bot.php`
2. Ganti `ISI_DENGAN_TOKEN_BOTMU` dengan token bot yang kamu dapatkan dari BotFather
3. Simpan file

```php
$token = "123456789:ABCdefGHIjklMNOpqrsTUVwxyz"; // Token kamu
```

### 3. Set Webhook
Bot perlu webhook untuk menerima pesan dari Telegram. Ada 2 cara:

#### Cara 1: Menggunakan URL Public (Recommended)
Jika bot kamu sudah di-deploy ke server dengan domain/URL public:

```bash
# Ganti <TOKEN> dengan token bot kamu
# Ganti <URL_BOT_KAMU> dengan URL lengkap ke bot.php
curl "https://api.telegram.org/bot<TOKEN>/setWebhook?url=<URL_BOT_KAMU>"
```

Contoh:
```bash
curl "https://api.telegram.org/bot123456789:ABCdefGHIjklMNOpqrsTUVwxyz/setWebhook?url=https://domainkamu.com/bot.php"
```

#### Cara 2: Menggunakan ngrok (Untuk Testing Lokal)
1. Download dan install [ngrok](https://ngrok.com/)
2. Jalankan ngrok untuk expose port lokal:
   ```bash
   ngrok http 80
   ```
3. Copy URL yang diberikan ngrok (misal: `https://abc123.ngrok.io`)
4. Set webhook:
   ```bash
   curl "https://api.telegram.org/bot<TOKEN>/setWebhook?url=https://abc123.ngrok.io/bot.php"
   ```

### 4. Test Bot
1. Buka Telegram dan cari bot kamu
2. Kirim command `/start`
3. Bot akan merespon dengan pesan selamat datang

## 🤖 Command yang Tersedia

- `/start` - Mulai bot dan lihat command yang tersedia
- `/help` - Tampilkan bantuan
- `/status` - Cek status bot
- Kirim pesan apapun untuk echo balik

## 📝 Fitur Bot

✅ Error handling yang robust
✅ Logging untuk debugging
✅ Support HTML formatting
✅ Multiple commands
✅ Echo pesan user

## 🔧 Troubleshooting

### Bot tidak merespon
1. Cek token bot sudah benar
2. Cek webhook sudah di-set dengan benar
3. Cek log file di `storage/logs/telegram_bot.log`
4. Pastikan server bisa diakses dari internet

### Cek webhook yang aktif
```bash
curl "https://api.telegram.org/bot<TOKEN>/getWebhookInfo"
```

### Hapus webhook (untuk testing)
```bash
curl "https://api.telegram.org/bot<TOKEN>/deleteWebhook"
```

## 📊 Logging

Bot akan membuat log file di: `storage/logs/telegram_bot.log`

Log berisi:
- Timestamp setiap update
- Pesan yang diterima
- Response yang dikirim
- Error jika ada

## 🚀 Deployment

### Untuk Production:
1. Pastikan menggunakan HTTPS (Telegram memerlukan HTTPS untuk webhook)
2. Set token bot di environment variable (lebih aman)
3. Enable error logging
4. Monitor log file secara berkala

### Menggunakan Environment Variable (Recommended)
Buat file `.env` dan tambahkan:
```
TELEGRAM_BOT_TOKEN=your_token_here
```

Lalu modifikasi `bot.php`:
```php
$token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? "ISI_DENGAN_TOKEN_BOTMU";
```

## 📚 Referensi

- [Telegram Bot API Documentation](https://core.telegram.org/bots/api)
- [BotFather Documentation](https://core.telegram.org/bots#6-botfather)


