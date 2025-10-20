#!/bin/bash

echo "========================================"
echo "   NGROK SETUP UNTUK BOT TELEGRAM"
echo "========================================"
echo

echo "Memulai ngrok untuk port 8000..."
echo
echo "Setelah ngrok berjalan, copy URL yang muncul"
echo "Contoh: https://abc123.ngrok.io"
echo
echo "Kemudian jalankan:"
echo "php scripts/set-telegram-webhook.php https://abc123.ngrok.io"
echo

ngrok http 8000
