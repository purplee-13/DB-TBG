<?php
$token = "ISI_DENGAN_TOKEN_BOTMU";
$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chat_id = $update["message"]["chat"]["id"];
$text = $update["message"]["text"];

if ($text == "/start") {
    $response = "Halo! Bot kamu sudah aktif 🚀";
} else {
    $response = "Kamu kirim: $text";
}

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($response));
?>

