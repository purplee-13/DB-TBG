<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

        $text = strtoupper($data['message']['text']); // Biar tidak case-sensitive
        Log::info('Processing text message:', ['text' => $text]);

        /**
         * Contoh format pesan:
         * VISIT SITE123 oleh Andi, tanggal 2025-10-16
         */
        if (preg_match('/VISIT\s+SITE(\d+)\s+OLEH\s+([A-Z ]+),\s*TANGGAL\s*(\d{4}-\d{2}-\d{2})/i', $text, $matches)) {
            $siteCode = 'SITE' . $matches[1];
            $teknisi = trim($matches[2]);
            $tglVisit = $matches[3];

            Log::info('Parsed message data:', [
                'site_code' => $siteCode,
                'teknisi' => $teknisi,
                'tgl_visit' => $tglVisit
            ]);

            $site = Site::where('site_code', $siteCode)->first();

            if ($site) {
                $site->update([
                    'progres' => 'Sudah Visit',
                    'nama_teknisi' => $teknisi,
                    'tgl_visit' => Carbon::parse($tglVisit)
                ]);

                Log::info('Site updated successfully', ['site_id' => $site->id]);
                $this->sendTelegramMessage("✅ Data {$siteCode} berhasil diperbarui oleh {$teknisi}!");
            } else {
                Log::warning('Site not found', ['site_code' => $siteCode]);
                $this->sendTelegramMessage("⚠️ Data dengan kode {$siteCode} tidak ditemukan di sistem.");
            }
        } else {
            Log::info('Message format not recognized', ['text' => $text]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendTelegramMessage($text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_CHAT_ID'); // opsional: bisa kamu set jika bot hanya boleh kirim ke 1 grup

        if (!$token) {
            Log::error('TELEGRAM_BOT_TOKEN not found in environment');
            return;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $text
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($url, ['form_params' => $data]);
            Log::info('Telegram message sent successfully', ['response' => $response->getBody()]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram message', ['error' => $e->getMessage()]);
        }
    }
}
