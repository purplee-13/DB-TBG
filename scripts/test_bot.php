<?php
/**
 * Test Script untuk Telegram Bot
 * 
 * Script ini membantu mengecek konfigurasi bot sebelum deploy
 */

echo "🤖 Telegram Bot Test Script\n";
echo str_repeat("=", 50) . "\n\n";

// 1. Cek file bot.php
echo "1. Checking bot.php file...\n";
$bot_file = __DIR__ . '/../public/bot.php';
if (file_exists($bot_file)) {
    echo "   ✅ File bot.php ditemukan\n";
    
    // Baca dan cek token
    $content = file_get_contents($bot_file);
    if (strpos($content, 'ISI_DENGAN_TOKEN_BOTMU') !== false) {
        echo "   ⚠️  Token bot belum di-set!\n";
        echo "   Silakan edit public/bot.php dan ganti token\n\n";
    } else {
        echo "   ✅ Token bot sudah di-set\n\n";
    }
} else {
    echo "   ❌ File bot.php tidak ditemukan!\n\n";
}

// 2. Cek extension PHP yang diperlukan
echo "2. Checking PHP extensions...\n";
$required_extensions = ['curl', 'json'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✅ Extension $ext tersedia\n";
    } else {
        echo "   ❌ Extension $ext tidak tersedia!\n";
    }
}
echo "\n";

// 3. Cek direktori logs
echo "3. Checking logs directory...\n";
$logs_dir = __DIR__ . '/../storage/logs';
if (is_dir($logs_dir)) {
    echo "   ✅ Direktori logs tersedia\n";
    if (is_writable($logs_dir)) {
        echo "   ✅ Direktori logs writable\n";
    } else {
        echo "   ⚠️  Direktori logs tidak writable\n";
    }
} else {
    echo "   ❌ Direktori logs tidak ditemukan!\n";
}
echo "\n";

// 4. Cek koneksi internet
echo "4. Checking internet connection...\n";
$ch = curl_init('https://api.telegram.org');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 || $http_code == 301 || $http_code == 302) {
    echo "   ✅ Koneksi ke Telegram API berhasil\n";
} else {
    echo "   ⚠️  Koneksi ke Telegram API bermasalah (HTTP $http_code)\n";
}
echo "\n";

// 5. Test bot token (optional)
echo "5. Test bot token (optional)...\n";
echo "   Masukkan token bot untuk test (atau tekan Enter untuk skip): ";
$token = trim(fgets(STDIN));

if (!empty($token)) {
    $url = "https://api.telegram.org/bot$token/getMe";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        $data = json_decode($result, true);
        if ($data && $data['ok']) {
            echo "   ✅ Token valid!\n";
            echo "   Bot name: " . $data['result']['first_name'] . "\n";
            echo "   Bot username: @" . $data['result']['username'] . "\n";
        } else {
            echo "   ❌ Token tidak valid!\n";
        }
    } else {
        echo "   ❌ Gagal koneksi ke Telegram API\n";
    }
} else {
    echo "   ⏭️  Test token di-skip\n";
}
echo "\n";

// 6. Summary
echo str_repeat("=", 50) . "\n";
echo "📋 Summary:\n";
echo "1. Edit public/bot.php dan set token bot\n";
echo "2. Deploy bot ke server atau gunakan ngrok untuk testing\n";
echo "3. Set webhook menggunakan curl command\n";
echo "4. Test bot di Telegram\n\n";
echo "📖 Lihat TELEGRAM_BOT_SETUP.md untuk panduan lengkap\n";
echo str_repeat("=", 50) . "\n";


