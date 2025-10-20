# Konfigurasi Ngrok untuk Bot Telegram

## 1. Install Ngrok

### Windows:
1. Download ngrok dari https://ngrok.com/download
2. Extract ke folder yang mudah diakses (misal: C:\ngrok)
3. Tambahkan path ngrok ke environment variables

### Atau menggunakan package manager:
```bash
# Menggunakan Chocolatey
choco install ngrok

# Menggunakan Scoop
scoop install ngrok
```

## 2. Setup Ngrok

1. Daftar akun di https://ngrok.com (gratis)
2. Dapatkan authtoken dari dashboard
3. Konfigurasi authtoken:
```bash
ngrok config add-authtoken YOUR_AUTHTOKEN
```

## 3. Menjalankan Ngrok

```bash
# Jalankan ngrok untuk port 8000 (sesuaikan dengan port Laravel Anda)
ngrok http 8000

# Atau untuk port 80
ngrok http 80
```

## 4. URL yang akan didapat

Setelah ngrok berjalan, Anda akan mendapat URL seperti:
```
https://abc123.ngrok.io
```

## 5. Set Webhook Telegram

Gunakan URL ngrok untuk set webhook:
```
https://api.telegram.org/bot<BOT_TOKEN>/setWebhook?url=https://abc123.ngrok.io/api/telegram/webhook
```

## 6. Testing

Kirim pesan ke bot untuk test webhook.
