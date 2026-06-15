<?php

namespace App\Exports;

use App\Models\Knmp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProgresTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Knmp::where('tahap_saat_ini', 'konstruksi')
            ->whereHas('konstruksiKnmp')
            ->get();
    }

    public function map($knmp): array
    {
        $kons = $knmp->konstruksiKnmp;
        return [
            $kons->id,
            $knmp->nama,
            date('Y-m-d'),
            '', // Leave progres empty
            ''  // Leave catatan empty
        ];
    }

    public function headings(): array
    {
        return [
            'ID Konstruksi (JANGAN DIUBAH)',
            'Nama Lokasi (JANGAN DIUBAH)',
            'Tanggal (YYYY-MM-DD)',
            'Progres Harian (%)',
            'Catatan'
        ];
    }
}
