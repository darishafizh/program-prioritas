<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsulanTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Lokasi Contoh', 'Jawa Barat', 'Kabupaten Bogor', 'Kecamatan Ciawi', 'Desa Bendungan', 'Pusat'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lokasi',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Status'
        ];
    }
}
