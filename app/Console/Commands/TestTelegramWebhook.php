<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram webhook with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = env('APP_URL') . '/api/telegram/webhook';
        
        if (!$webhookUrl) {
            $this->error('APP_URL not found in environment');
            return 1;
        }

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

        $this->info("Testing webhook with URL: {$webhookUrl}");
        $this->info("Sample data: " . json_encode($sampleData, JSON_PRETTY_PRINT));

        try {
            $response = Http::post($webhookUrl, $sampleData);
            
            $this->info("Response Status: " . $response->status());
            $this->info("Response Body: " . $response->body());
            
            if ($response->successful()) {
                $this->info("✅ Webhook test successful!");
            } else {
                $this->error("❌ Webhook test failed!");
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        return 0;
    }
}
