<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoogleSheetsWebhookController extends Controller
{
    /**
     * Menerima payload dari Google Apps Script saat ada perubahan di Google Sheets.
     */
    public function sync(Request $request)
    {
        try {
            $payload = $request->all();

            // Mendukung parsing JSON jika data dikirim sebagai string raw di dalam body
            if (empty($payload) && !empty($request->getContent())) {
                $payload = json_decode($request->getContent(), true);
            }

            Log::info('Google Sheets Webhook Received:', $payload);

            // Kolom identifier
            $namaKampung = $payload['Kampung'] ?? $payload['kampung'] ?? $payload['Nama Kampung'] ?? null;

            if (!$namaKampung) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identifier "Kampung" tidak ditemukan di payload.'
                ], 400);
            }

            // Cari ID knmp berdasarkan nama
            $knmp = DB::connection('mysql_knmp')
                ->table('knmp')
                ->where('nama', 'LIKE', '%' . $namaKampung . '%')
                ->first();

            if (!$knmp) {
                return response()->json([
                    'success' => false,
                    'message' => "Data KNMP dengan nama '{$namaKampung}' tidak ditemukan."
                ], 404);
            }

            $updateData = [
                'jml_kk' => $this->parseNumber($payload['Jumlah KK'] ?? null),
                'jml_nelayan' => $this->parseNumber($payload['Jumlah Nelayan'] ?? null),
                'komoditas' => $payload['Komoditas Utama'] ?? null,
                'penjualan_ikan' => $payload['Penjualan Ikan'] ?? null,
                'jml_hari_melaut' => $this->parseNumber($payload['Jum. Hari Melaut'] ?? null),
                'pend_avg_saat_ini' => $this->parseNumber($payload['Pendpt. Rata2 Saat Ini'] ?? null),
                'pend_avg_intervensi' => $this->parseNumber($payload['Pendpt. Pasca Intervensi'] ?? null),
                'serapan_tenaga_kerja' => $this->parseNumber($payload['Serapan Tenaga Kerja'] ?? null),
                'vol_produksi_daerah' => $this->parseNumber($payload['Vol. Produksi Daerah'] ?? null),
                'nilai_produksi_daerah' => $this->parseNumber($payload['Nilai Produksi Daerah'] ?? null),
                'vol_produksi_intervensi' => $this->parseNumber($payload['Vol. Prod Pasca Inter.'] ?? null),
                'nilai_produksi_intervensi' => $this->parseNumber($payload['Nilai Prod Pasca Inter.'] ?? null),
                'updated_at' => now(),
            ];

            // Filter nilai null
            $updateData = array_filter($updateData, function ($val) {
                return $val !== null && $val !== '';
            });

            if (empty($updateData)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada kolom profil yang diupdate dari payload.'
                ], 200);
            }

            // Update profil
            $affected = DB::connection('mysql_knmp')
                ->table('profil_knmp')
                ->where('knmp_id', $knmp->id)
                ->update($updateData);

            // Jika belum ada record di profil_knmp, lakukan insert
            if ($affected === 0) {
                $exists = DB::connection('mysql_knmp')
                    ->table('profil_knmp')
                    ->where('knmp_id', $knmp->id)
                    ->exists();

                if (!$exists) {
                    $updateData['knmp_id'] = $knmp->id;
                    $updateData['created_at'] = now();
                    DB::connection('mysql_knmp')->table('profil_knmp')->insert($updateData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Profil KNMP '{$namaKampung}' berhasil disinkronkan dari Google Sheets.",
                'data_updated' => $updateData
            ], 200);

        } catch (\Exception $e) {
            Log::error('Google Sheets Webhook Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat sinkronisasi data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Utility untuk membersihkan string format angka
     */
    private function parseNumber($val)
    {
        if ($val === null || $val === '') return null;
        if (is_numeric($val)) return (float) $val;
        
        return floatval(str_replace(['Rp', ' ', ','], '', $val));
    }
}
