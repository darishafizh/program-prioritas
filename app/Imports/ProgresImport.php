<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgresImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $inserts = [];
        $now = now();

        foreach ($rows as $row) {
            // Slugified headers
            $id = $row['id_konstruksi_jangan_diubah'] ?? null;
            $tanggal = $row['tanggal_yyyy_mm_dd'] ?? null;
            $progres = $row['progres_harian'] ?? null;
            $catatan = $row['catatan'] ?? '';

            if (!$id || !$tanggal || $progres === null || $progres === '') {
                continue;
            }

            $inserts[] = [
                'knmp_konstruksi_id' => $id,
                'tanggal' => date('Y-m-d', strtotime($tanggal)),
                'progres' => (float) $progres,
                'catatan' => $catatan,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($inserts) > 0) {
            DB::connection('mysql_knmp')->table('progres_harian')->insert($inserts);
        }
    }
}
