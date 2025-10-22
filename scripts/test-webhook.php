<?php

/**
 * Script untuk test webhook Telegram
 * Usage: php scripts/test-webhook.php <webhook_url>
 */

require_once __DIR__ . '/../vendor/autoload.php';

if ($argc < 2) {
    echo "Usage: php scripts/test-webhook.php <webhook_url>\n";
    echo "Example: php scripts/test-webhook.php https://abc123.ngrok-free.app/api/telegram/webhook\n";
    exit(1);
}

$webhookUrl = $argv[1];

echo "🧪 Testing webhook...\n";
echo "URL: {$webhookUrl}\n\n";

// Sample webhook data
$sampleData = [
    'update_id' => 123456789,
    'message' => [
        'message_id' => 1,
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Test',
            'username' => 'testuser'
        ],
        'chat' => [
            'id' => -1001234567890,
            'title' => 'Test Group',
            'type' => 'supergroup'
        ],
        'date' => time(),
        'text' => '/update 1 Sudah Visit'
    ]
];

echo "📤 Sending sample data:\n";
echo json_encode($sampleData, JSON_PRETTY_PRINT) . "\n\n";

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sampleData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($sampleData))
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ cURL Error: {$error}\n";
        exit(1);
    }
    
    echo "📥 Response:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Body: {$response}\n\n";
    
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ Webhook test successful!\n";
        echo "Bot should respond to the test message.\n";
    } else {
        echo "❌ Webhook test failed!\n";
        echo "Check your Laravel logs for more details.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}