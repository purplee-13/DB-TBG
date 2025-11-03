<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;
use App\Models\TelegramChat;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Telegram webhook receiver
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Telegram update received', $payload);

        // Basic validation of update structure
        if (!isset($payload['message'])) {
            return response('ok', 200);
        }

    $chatId = $payload['message']['chat']['id'] ?? null;
        $text = trim($payload['message']['text'] ?? '');

        if (!$text) {
            $this->telegram->sendMessage($chatId, 'Tidak ada perintah yang diterima.');
            return response('ok', 200);
        }

        // Admin commands
        if (str_starts_with($text, '/register_chat')) {
            // Usage: /register_chat optional_label
            $parts = preg_split('/\s+/', $text, 2);
            $label = $parts[1] ?? null;
            TelegramChat::updateOrCreate(['chat_id' => (string)$chatId], ['label' => $label]);
            $this->telegram->sendMessage($chatId, 'Chat ini telah didaftarkan untuk mengakses fitur bot.');
            return response('ok', 200);
        }

        if (str_starts_with($text, '/list_chats')) {
            $owner = env('TELEGRAM_OWNER_ID');
            if ((string)$chatId !== (string)$owner) {
                $this->telegram->sendMessage($chatId, 'Hanya owner yang dapat melihat daftar chat yang terdaftar.');
                return response('ok', 200);
            }
            $rows = TelegramChat::all();
            if ($rows->isEmpty()) {
                $this->telegram->sendMessage($chatId, 'Belum ada chat yang terdaftar.');
                return response('ok', 200);
            }
            $msg = "Daftar chat terdaftar:\n";
            foreach ($rows as $r) {
                $msg .= "{$r->chat_id} - {$r->label}\n";
            }
            $this->telegram->sendMessage($chatId, $msg);
            return response('ok', 200);
        }

        // Commands:
        // /site <site_code|site_name>
        // /update_maintenance <site_code> <progres>

    if (str_starts_with($text, '/site')) {
            $parts = preg_split('/\s+/', $text, 2);
            $query = $parts[1] ?? null;
            if (!$query) {
                $this->telegram->sendMessage($chatId, "Gunakan: /site <site_code|site_name>");
                return response('ok', 200);
            }

            $site = Site::where('site_code', $query)
                        ->orWhere('site_name', 'like', "%{$query}%")
                        ->first();

            if (!$site) {
                $this->telegram->sendMessage($chatId, "Site tidak ditemukan untuk: {$query}");
                return response('ok', 200);
            }

            $msg = "Site: {$site->site_name} (ID: {$site->site_code})\nArea: {$site->service_area}\nSTO: {$site->sto}\nProduct: {$site->product}\nTIKOR: {$site->tikor}";
            $this->telegram->sendMessage($chatId, $msg);
            return response('ok', 200);
        }

    if (str_starts_with($text, '/update_maintenance')) {
            // Format: /update_maintenance <site_code> <progres> [teknisi=Nama] [keterangan=Text]
            $parts = preg_split('/\s+/', $text);
            $siteCode = $parts[1] ?? null;
            $progres = $parts[2] ?? null;

            // parse optional key=value pairs from remaining parts
            $opts = [];
            for ($i = 3; $i < count($parts); $i++) {
                if (strpos($parts[$i], '=') !== false) {
                    [$k, $v] = explode('=', $parts[$i], 2);
                    $opts[strtolower($k)] = $v;
                }
            }

            $site = Site::where('site_code', $siteCode)->first();
            if (!$site) {
                $this->telegram->sendMessage($chatId, "Site dengan kode {$siteCode} tidak ditemukan.");
                return response('ok', 200);
            }

            // Authorization: check DB first, then env allowlist
            $allowedInDb = TelegramChat::where('chat_id', (string)$chatId)->exists();
            $allowed = env('ALLOWED_TELEGRAM_CHAT_IDS');
            $allowedInEnv = false;
            if ($allowed) {
                $allowedIds = array_map('trim', explode(',', $allowed));
                $allowedInEnv = in_array((string)$chatId, $allowedIds);
            }
            if (! $allowedInDb && ! $allowedInEnv) {
                $this->telegram->sendMessage($chatId, "Anda tidak diizinkan untuk melakukan aksi ini. Gunakan /register_chat di chat yang ingin Anda izinkan (atau mintakan owner untuk mendaftarkan chat).");
                return response('ok', 200);
            }

            // Upsert maintenance for today for this site
            $today = now()->toDateString();
            $maintenance = Maintenance::firstOrNew([
                'site_id' => $site->id,
                'tngl_visit' => $today,
            ]);
            $maintenance->progres = $progres;
            if (isset($opts['teknisi'])) $maintenance->teknisi = $opts['teknisi'];
            if (isset($opts['keterangan'])) $maintenance->keterangan = $opts['keterangan'];
            $maintenance->operator = 'telegram_bot';
            $maintenance->save();

            $this->telegram->sendMessage($chatId, "Maintenance untuk site {$site->site_code} diperbarui: {$progres}");
            return response('ok', 200);
        }

        // Unknown command
        $this->telegram->sendMessage($chatId, 'Perintah tidak dikenali. Gunakan /site atau /update_maintenance');
        return response('ok', 200);
    }
}