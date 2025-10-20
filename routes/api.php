<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TelegramController;

// Route untuk webhook Telegram (bypass semua middleware)
Route::post('/telegram/webhook', function(Request $request) {
    // Log semua request untuk debugging
    \Log::info('Telegram webhook received:', $request->all());
    
    $data = $request->all();

    // Pastikan pesan berasal dari teks chat
    if (!isset($data['message']['text'])) {
        \Log::info('No text message received');
        return response()->json(['message' => 'No text'], 200);
    }

    $text = strtoupper($data['message']['text']); // Biar tidak case-sensitive
    \Log::info('Processing text message:', ['text' => $text]);

    /**
     * Contoh format pesan:
     * VISIT SITE123 oleh Andi, tanggal 2025-10-16
     */
    if (preg_match('/VISIT\s+SITE(\d+)\s+OLEH\s+([A-Z ]+),\s*TANGGAL\s*(\d{4}-\d{2}-\d{2})/i', $text, $matches)) {
        $siteCode = 'SITE' . $matches[1];
        $teknisi = trim($matches[2]);
        $tglVisit = $matches[3];

        \Log::info('Parsed message data:', [
            'site_code' => $siteCode,
            'teknisi' => $teknisi,
            'tgl_visit' => $tglVisit
        ]);

        $site = \App\Models\Site::where('site_code', $siteCode)->first();

        if ($site) {
            $site->update([
                'progres' => 'Sudah Visit',
                'nama_teknisi' => $teknisi,
                'tgl_visit' => \Carbon\Carbon::parse($tglVisit)
            ]);

            \Log::info('Site updated successfully', ['site_id' => $site->id]);
            // Kirim response sukses
            return response()->json(['status' => 'ok', 'message' => "Data {$siteCode} berhasil diperbarui oleh {$teknisi}!"]);
        } else {
            \Log::warning('Site not found', ['site_code' => $siteCode]);
            return response()->json(['status' => 'error', 'message' => "Data dengan kode {$siteCode} tidak ditemukan di sistem."]);
        }
    } else {
        \Log::info('Message format not recognized', ['text' => $text]);
        return response()->json(['status' => 'ok', 'message' => 'Message format not recognized']);
    }
});
