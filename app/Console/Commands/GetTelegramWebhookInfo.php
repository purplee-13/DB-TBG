<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetTelegramWebhookInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Telegram webhook information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN not found in environment');
            return 1;
        }

        $webhookUrl = "https://api.telegram.org/bot{$token}/getWebhookInfo";

        try {
            $response = Http::get($webhookUrl);
            $result = $response->json();

            if ($result['ok']) {
                $webhookInfo = $result['result'];
                $this->info("Webhook URL: " . ($webhookInfo['url'] ?: 'Not set'));
                $this->info("Has custom certificate: " . ($webhookInfo['has_custom_certificate'] ? 'Yes' : 'No'));
                $this->info("Pending update count: " . $webhookInfo['pending_update_count']);
                $this->info("Last error date: " . ($webhookInfo['last_error_date'] ? date('Y-m-d H:i:s', $webhookInfo['last_error_date']) : 'None'));
                $this->info("Last error message: " . ($webhookInfo['last_error_message'] ?: 'None'));
                $this->info("Max connections: " . $webhookInfo['max_connections']);
                $this->info("Allowed updates: " . json_encode($webhookInfo['allowed_updates'] ?? []));
            } else {
                $this->error("Gagal mendapatkan informasi webhook: " . $result['description']);
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        return 0;
    }
}
