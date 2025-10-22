<?php

/**
 * Script untuk setup webhook Telegram
 * Usage: php scripts/setup-telegram-webhook.php <ngrok_url>
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if ($argc < 2) {
    echo "Usage: php scripts/setup-telegram-webhook.php <ngrok_url>\n";
    echo "Example: php scripts/setup-telegram-webhook.php https://abc123.ngrok-free.app\n";
    exit(1);
}

$ngrokUrl = $argv[1];
$webhookUrl = $ngrokUrl . '/api/telegram/webhook';
$token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;

if (!$token) {
    echo "❌ TELEGRAM_BOT_TOKEN not found in .env file\n";
    exit(1);
}

echo "🚀 Setting up Telegram webhook...\n";
echo "Webhook URL: {$webhookUrl}\n";
echo "Bot Token: " . substr($token, 0, 10) . "...\n\n";

try {
    $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
        'url' => $webhookUrl
    ]);

    $result = $response->json();

    if ($result['ok']) {
        echo "✅ Webhook berhasil diatur!\n";
        echo "URL: {$webhookUrl}\n";
        
        // Cek info webhook
        $infoResponse = Http::get("https://api.telegram.org/bot{$token}/getWebhookInfo");
        $info = $infoResponse->json();
        
        if ($info['ok']) {
            $webhookInfo = $info['result'];
            echo "\n📊 Webhook Info:\n";
            echo "URL: " . ($webhookInfo['url'] ?: 'Not set') . "\n";
            echo "Pending updates: " . $webhookInfo['pending_update_count'] . "\n";
            echo "Last error: " . ($webhookInfo['last_error_message'] ?: 'None') . "\n";
        }
        
        echo "\n🎉 Setup selesai! Bot siap digunakan.\n";
        echo "Test dengan mengirim: /update 1 Sudah Visit\n";
        
    } else {
        echo "❌ Gagal mengatur webhook: " . $result['description'] . "\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
