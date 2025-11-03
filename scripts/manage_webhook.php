<?php
/**
 * Webhook Management Script
 * 
 * Script untuk mengelola webhook bot Telegram
 */

echo "🔗 Telegram Bot Webhook Manager\n";
echo str_repeat("=", 50) . "\n\n";

// Fungsi untuk call Telegram API
function callTelegramAPI($token, $method, $params = []) {
    $url = "https://api.telegram.org/bot$token/$method";
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $http_code,
        'result' => json_decode($result, true)
    ];
}

// Input token
echo "Masukkan bot token: ";
$token = trim(fgets(STDIN));

if (empty($token)) {
    echo "❌ Token tidak boleh kosong!\n";
    exit(1);
}

// Menu
echo "\nPilih aksi:\n";
echo "1. Set webhook\n";
echo "2. Get webhook info\n";
echo "3. Delete webhook\n";
echo "4. Exit\n\n";
echo "Pilihan (1-4): ";
$choice = trim(fgets(STDIN));

switch ($choice) {
    case '1':
        echo "\n--- Set Webhook ---\n";
        echo "Masukkan URL webhook (contoh: https://domainkamu.com/bot.php): ";
        $webhook_url = trim(fgets(STDIN));
        
        if (empty($webhook_url)) {
            echo "❌ URL tidak boleh kosong!\n";
            exit(1);
        }
        
        echo "\n⏳ Mengirim request ke Telegram...\n";
        $result = callTelegramAPI($token, 'setWebhook', [
            'url' => $webhook_url
        ]);
        
        if ($result['http_code'] == 200 && $result['result']['ok']) {
            echo "✅ Webhook berhasil di-set!\n";
            echo "URL: $webhook_url\n";
            echo "Description: " . ($result['result']['description'] ?? 'N/A') . "\n";
        } else {
            echo "❌ Gagal set webhook!\n";
            if (isset($result['result']['description'])) {
                echo "Error: " . $result['result']['description'] . "\n";
            }
        }
        break;
        
    case '2':
        echo "\n--- Get Webhook Info ---\n";
        echo "⏳ Mengambil info webhook...\n";
        $result = callTelegramAPI($token, 'getWebhookInfo');
        
        if ($result['http_code'] == 200 && $result['result']['ok']) {
            $info = $result['result']['result'];
            echo "\n📊 Webhook Info:\n";
            echo str_repeat("-", 30) . "\n";
            echo "URL: " . ($info['url'] ?? 'Not set') . "\n";
            echo "Has custom certificate: " . ($info['has_custom_certificate'] ? 'Yes' : 'No') . "\n";
            echo "Pending update count: " . ($info['pending_update_count'] ?? 0) . "\n";
            
            if (isset($info['last_error_date'])) {
                echo "\n⚠️  Last Error:\n";
                echo "Date: " . date('Y-m-d H:i:s', $info['last_error_date']) . "\n";
                echo "Message: " . $info['last_error_message'] . "\n";
            }
            
            if (isset($info['last_synchronization_error_date'])) {
                echo "\n⚠️  Last Sync Error:\n";
                echo "Date: " . date('Y-m-d H:i:s', $info['last_synchronization_error_date']) . "\n";
            }
        } else {
            echo "❌ Gagal mengambil info webhook!\n";
        }
        break;
        
    case '3':
        echo "\n--- Delete Webhook ---\n";
        echo "⚠️  Apakah kamu yakin ingin menghapus webhook? (y/n): ";
        $confirm = trim(fgets(STDIN));
        
        if (strtolower($confirm) !== 'y') {
            echo "❌ Dibatalkan\n";
            exit(0);
        }
        
        echo "\n⏳ Menghapus webhook...\n";
        $result = callTelegramAPI($token, 'deleteWebhook', [
            'drop_pending_updates' => true
        ]);
        
        if ($result['http_code'] == 200 && $result['result']['ok']) {
            echo "✅ Webhook berhasil dihapus!\n";
        } else {
            echo "❌ Gagal menghapus webhook!\n";
        }
        break;
        
    case '4':
        echo "👋 Exit\n";
        exit(0);
        break;
        
    default:
        echo "❌ Pilihan tidak valid!\n";
        exit(1);
}

echo "\n" . str_repeat("=", 50) . "\n";


