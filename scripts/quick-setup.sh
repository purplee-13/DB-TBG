#!/bin/bash

echo "========================================"
echo "   QUICK SETUP TELEGRAM BOT"
echo "========================================"
echo

echo "1. Cek file .env..."
if [ ! -f ".env" ]; then
    echo "❌ File .env tidak ditemukan!"
    echo "Silakan copy .env.example ke .env dan isi konfigurasi"
    echo
    echo "Tambahkan ke .env:"
    echo "TELEGRAM_BOT_TOKEN=your_bot_token_here"
    echo "TELEGRAM_CHAT_ID=your_chat_id_here"
    echo
    exit 1
fi

echo "✅ File .env ditemukan"
echo

echo "2. Cek token bot..."
if ! grep -q "TELEGRAM_BOT_TOKEN" .env; then
    echo "❌ TELEGRAM_BOT_TOKEN tidak ditemukan di .env!"
    echo "Silakan tambahkan TELEGRAM_BOT_TOKEN=your_token_here ke .env"
    exit 1
fi

echo "✅ Token bot ditemukan"
echo

echo "3. Start Laravel server..."
echo "Memulai Laravel di background..."
php artisan serve > /dev/null 2>&1 &
LARAVEL_PID=$!
sleep 3

echo "✅ Laravel server berjalan (PID: $LARAVEL_PID)"
echo

echo "4. Start ngrok..."
echo "Memulai ngrok di background..."
ngrok http 8000 > /dev/null 2>&1 &
NGROK_PID=$!
sleep 5

echo "✅ ngrok berjalan (PID: $NGROK_PID)"
echo

echo "5. Cek status ngrok..."
sleep 2
NGROK_URL=$(curl -s http://localhost:4040/api/tunnels | jq -r '.tunnels[] | select(.proto=="https") | .public_url' | head -1)

if [ -z "$NGROK_URL" ]; then
    echo "❌ Gagal mendapatkan URL ngrok"
    echo "Silakan cek manual di http://localhost:4040"
    kill $LARAVEL_PID $NGROK_PID 2>/dev/null
    exit 1
fi

echo "✅ URL ngrok: $NGROK_URL"
echo

echo "6. Set webhook..."
php artisan telegram:set-webhook "$NGROK_URL/api/telegram/webhook"
if [ $? -ne 0 ]; then
    echo "❌ Gagal set webhook"
    kill $LARAVEL_PID $NGROK_PID 2>/dev/null
    exit 1
fi

echo "✅ Webhook berhasil diatur"
echo

echo "7. Cek status webhook..."
php artisan telegram:webhook-info
echo

echo "========================================"
echo "    SETUP SELESAI!"
echo "========================================"
echo
echo "Bot siap digunakan!"
echo "URL webhook: $NGROK_URL/api/telegram/webhook"
echo
echo "Test dengan mengirim: /update 1 Sudah Visit"
echo
echo "Tekan Ctrl+C untuk menghentikan semua proses"
echo

# Keep processes running
wait $LARAVEL_PID $NGROK_PID
