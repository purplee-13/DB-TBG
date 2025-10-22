<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Telegram webhook received:', $request->all());
        
        $data = $request->all();

        // Pastikan pesan berasal dari teks chat
        if (!isset($data['message']['text'])) {
            Log::info('No text message received');
            return response()->json(['message' => 'No text'], 200);
        }

        $text = $data['message']['text'];
        $chatId = $data['message']['chat']['id'];
        
        Log::info('Processing text message:', ['text' => $text, 'chat_id' => $chatId]);

        // Parsing perintah /update <site_id> <status>
        if (preg_match('/^\/update\s+([A-Z0-9_-]+)\s+(.+)$/i', $text, $matches)) {
            $siteCode = strtoupper(trim($matches[1]));
            $status = trim($matches[2]);
            
            Log::info('Parsed update command:', [
                'site_code' => $siteCode,
                'status' => $status
            ]);

            $site = Site::where('site_code', $siteCode)->first();

            if ($site) {
                // Update data site
                $site->update([
                    'progres' => $status,
                    'tgl_visit' => Carbon::now()
                ]);

                Log::info('Site updated successfully', ['site_code' => $site->id]);
                
                // Kirim konfirmasi ke grup
                $confirmMessage = "✅ *Site {$site->nama_site}* berhasil diperbarui menjadi *{$status}* pada *" . Carbon::now()->format('d-m-Y H:i') . "*.";
                $this->sendTelegramMessage($chatId, $confirmMessage);
                
                // Kirim ringkasan total site
                $this->sendSiteSummary($chatId);
                
            } else {
                Log::warning('Site not found', ['site_code' => $siteCode]);
                $this->sendTelegramMessage($chatId, "⚠️ Site dengan ID {$siteCode} tidak ditemukan di sistem.");
            }
        } else {
            Log::info('Message format not recognized', ['text' => $text]);
            $this->sendTelegramMessage($chatId, "Format perintah tidak dikenali. Gunakan: /update <site_code> <status>");
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendTelegramMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            Log::error('TELEGRAM_BOT_TOKEN not found in environment');
            return;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ];

        try {
            $response = Http::post($url, $data);
            Log::info('Telegram message sent successfully', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram message', ['error' => $e->getMessage()]);
        }
    }

    private function sendSiteSummary($chatId)
    {
        $totalSites = Site::count();
        $visitedSites = Site::where('progres', 'Sudah Visit')->count();
        
        $summaryMessage = "📊 Total site yang sudah visit: {$visitedSites} dari {$totalSites} site.";
        $this->sendTelegramMessage($chatId, $summaryMessage);
    }
}
