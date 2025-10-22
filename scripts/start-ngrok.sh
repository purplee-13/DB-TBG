#!/bin/bash

echo "========================================"
echo "   NGROK SETUP UNTUK BOT TELEGRAM"
echo "========================================"
echo

echo "Memulai ngrok untuk port 8000..."
echo
echo "Setelah ngrok berjalan, copy URL yang muncul"
echo "Contoh: https://abc123.ngrok-free.app"
echo
echo "Kemudian jalankan:"
echo "php artisan telegram:set-webhook https://abc123.ngrok-free.app/api/telegram/webhook"
echo

ngrok http 8000
