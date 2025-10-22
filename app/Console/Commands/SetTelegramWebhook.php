<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram webhook URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN not found in environment');
            return 1;
        }

        $webhookUrl = "https://api.telegram.org/bot{$token}/setWebhook";
        $data = [
            'url' => $url
        ];

        try {
            $response = Http::post($webhookUrl, $data);
            $result = $response->json();

            if ($result['ok']) {
                $this->info("Webhook berhasil diatur ke: {$url}");
                Log::info('Telegram webhook set successfully', ['url' => $url]);
            } else {
                $this->error("Gagal mengatur webhook: " . $result['description']);
                Log::error('Failed to set Telegram webhook', ['error' => $result['description']]);
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error('Exception while setting Telegram webhook', ['error' => $e->getMessage()]);
        }

        return 0;
    }
}
