<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Carbon\Carbon;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        // Pastikan pesan berasal dari teks chat
        if (!isset($data['message']['text'])) {
            return response()->json(['message' => 'No text'], 200);
        }

        $text = strtoupper($data['message']['text']); // Biar tidak case-sensitive

        /**
         * Contoh format pesan:
         * VISIT SITE123 oleh Andi, tanggal 2025-10-16
         */
        if (preg_match('/VISIT\s+SITE(\d+)\s+OLEH\s+([A-Z ]+),\s*TANGGAL\s*(\d{4}-\d{2}-\d{2})/i', $text, $matches)) {
            $siteCode = 'SITE' . $matches[1];
            $teknisi = trim($matches[2]);
            $tglVisit = $matches[3];

            $site = Site::where('site_code', $siteCode)->first();

            if ($site) {
                $site->update([
                    'progres' => 'Sudah Visit',
                    'nama_teknisi' => $teknisi,
                    'tgl_visit' => Carbon::parse($tglVisit)
                ]);

                $this->sendTelegramMessage("✅ Data {$siteCode} berhasil diperbarui oleh {$teknisi}!");
            } else {
                $this->sendTelegramMessage("⚠️ Data dengan kode {$siteCode} tidak ditemukan di sistem.");
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendTelegramMessage($text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_CHAT_ID'); // opsional: bisa kamu set jika bot hanya boleh kirim ke 1 grup

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $text
        ];

        $client = new \GuzzleHttp\Client();
        $client->post($url, ['form_params' => $data]);
    }
}

