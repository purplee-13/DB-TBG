<?php
/**
 * Script untuk set webhook Telegram dengan ngrok
 * 
 * Cara penggunaan:
 * 1. Jalankan ngrok: ngrok http 8000
 * 2. Copy URL ngrok (misal: https://abc123.ngrok.io)
 * 3. Jalankan script ini: php scripts/set-telegram-webhook.php https://abc123.ngrok.io
 */

// Cek apakah URL ngrok diberikan
if ($argc < 2) {
    echo "❌ Error: URL ngrok tidak diberikan!\n";
    echo "Cara penggunaan: php scripts/set-telegram-webhook.php <NGROK_URL>\n";
    echo "Contoh: php scripts/set-telegram-webhook.php https://abc123.ngrok.io\n";
    exit(1);
}

$ngrokUrl = $argv[1];

// Validasi URL ngrok
if (!filter_var($ngrokUrl, FILTER_VALIDATE_URL)) {
    echo "❌ Error: URL ngrok tidak valid!\n";
    exit(1);
}

// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;

if (!$botToken) {
    echo "❌ Error: TELEGRAM_BOT_TOKEN tidak ditemukan di .env file!\n";
    echo "Pastikan file .env memiliki: TELEGRAM_BOT_TOKEN=your_bot_token\n";
    exit(1);
}

// URL webhook
$webhookUrl = rtrim($ngrokUrl, '/') . '/telegram/webhook';

echo "🚀 Setting webhook untuk bot Telegram...\n";
echo "📡 Bot Token: " . substr($botToken, 0, 10) . "...\n";
echo "🔗 Webhook URL: $webhookUrl\n\n";

// Set webhook
$apiUrl = "https://api.telegram.org/bot{$botToken}/setWebhook";
$data = [
    'url' => $webhookUrl
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    
    if ($result['ok']) {
        echo "✅ Webhook berhasil diset!\n";
        echo "📋 Response: " . $result['description'] . "\n";
        
        // Test webhook
        echo "\n🧪 Testing webhook...\n";
        $testUrl = "https://api.telegram.org/bot{$botToken}/getWebhookInfo";
        $testResponse = file_get_contents($testUrl);
        $testResult = json_decode($testResponse, true);
        
        if ($testResult['ok']) {
            echo "✅ Webhook info:\n";
            echo "   URL: " . $testResult['result']['url'] . "\n";
            echo "   Pending updates: " . $testResult['result']['pending_update_count'] . "\n";
            echo "   Last error: " . ($testResult['result']['last_error_message'] ?? 'None') . "\n";
        }
    } else {
        echo "❌ Error: " . $result['description'] . "\n";
    }
} else {
    echo "❌ Error: HTTP $httpCode\n";
    echo "Response: $response\n";
}

echo "\n📝 Catatan:\n";
echo "- Pastikan ngrok masih berjalan\n";
echo "- Pastikan Laravel server berjalan di port yang sesuai\n";
echo "- Test dengan mengirim pesan ke bot\n";
