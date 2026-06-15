<?php

namespace App\Imports;

use App\Models\Knmp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsulanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama_lokasi']) || empty(trim($row['nama_lokasi']))) {
            return null;
        }

        return new Knmp([
            'tahap_saat_ini' => 'usulan',
            'nama' => $row['nama_lokasi'],
            'provinsi' => $row['provinsi'] ?? '-',
            'kabupaten' => $row['kabupaten'] ?? '-',
            'kecamatan' => $row['kecamatan'] ?? '-',
            'desa' => $row['desa'] ?? '-',
            'status' => $row['status'] ?? 'Penyangga',
        ]);
    }
}
