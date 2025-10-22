<?php

namespace App\Services;

class TelegramService
{
    protected $token;

    public function __construct()
    {
        $this->token = env('TELEGRAM_BOT_TOKEN');
    }

    protected function apiUrl($method)
    {
        return "https://api.telegram.org/bot{$this->token}/{$method}";
    }

    public function sendMessage($chatId, $text)
    {
        if (empty($this->token) || empty($chatId)) return false;

        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        // Prefer Guzzle if available
        if (class_exists('\GuzzleHttp\Client')) {
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->post($this->apiUrl('sendMessage'), [
                    'form_params' => $data,
                    'timeout' => 5,
                ]);
                return $res->getStatusCode() === 200;
            } catch (\Exception $e) {
                // fallback
            }
        }

        // Simple fallback using file_get_contents
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded\r\n',
                'content' => http_build_query($data),
                'timeout' => 5,
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($this->apiUrl('sendMessage'), false, $context);
        return $result !== false;
    }
}
