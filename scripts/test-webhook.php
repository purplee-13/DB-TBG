<?php
/**
 * Script untuk test webhook Telegram
 * 
 * Cara penggunaan:
 * php scripts/test-webhook.php <NGROK_URL>
 */

if ($argc < 2) {
    echo "❌ Error: URL ngrok tidak diberikan!\n";
    echo "Cara penggunaan: php scripts/test-webhook.php <NGROK_URL>\n";
    echo "Contoh: php scripts/test-webhook.php https://abc123.ngrok.io\n";
    exit(1);
}

$ngrokUrl = $argv[1];
$webhookUrl = rtrim($ngrokUrl, '/') . '/telegram/webhook';

echo "🧪 Testing webhook: $webhookUrl\n\n";

// Test data (simulasi pesan dari Telegram)
$testData = [
    'message' => [
        'text' => 'VISIT SITE123 oleh Andi, tanggal 2025-10-16',
        'chat' => [
            'id' => 123456789
        ],
        'from' => [
            'id' => 987654321,
            'first_name' => 'Test User'
        ]
    ]
];

// Kirim test request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: TelegramBot'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Test Results:\n";
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if ($error) {
    echo "❌ cURL Error: $error\n";
} elseif ($httpCode === 200) {
    echo "✅ Webhook test berhasil!\n";
} else {
    echo "❌ Webhook test gagal!\n";
}

echo "\n📝 Catatan:\n";
echo "- Pastikan Laravel server berjalan\n";
echo "- Pastikan ngrok masih aktif\n";
echo "- Cek log Laravel: tail -f storage/logs/laravel.log\n";
