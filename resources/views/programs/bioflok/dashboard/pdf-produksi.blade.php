<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produksi KDKMP Bioflok</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; text-transform: uppercase; }
        .sub-header { text-align: center; margin-bottom: 15px; }
        .sub-header h3 { margin: 0; font-size: 13px; text-transform: uppercase; color: #0891B2; }
        .sub-header p { margin: 4px 0; font-size: 10px; }
        .summary { margin-bottom: 15px; text-align: center; }
        .summary span { display: inline-block; margin: 0 6px; padding: 4px 10px; background-color: #f1f5f9; border-radius: 4px; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #ddd; padding: 7px 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f8fafc; text-align: center; font-weight: bold; font-size: 9px; color: #334155; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-success { color: #16a34a; font-weight: bold; }
        .text-muted { color: #64748b; }
        tfoot tr { background-color: #f1f5f9; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header" style="position: relative; min-height: 50px;">
        @php
            $bgPath = public_path('assets/images/logo-kkp.png');
            if (file_exists($bgPath)) {
                $bgData = base64_encode(file_get_contents($bgPath));
                echo '<img src="data:image/png;base64,'.$bgData.'" style="height: 50px; position: absolute; left: 20px; top: 0;">';
            }
        @endphp
        <h1>Sekretariat Jenderal</h1>
        <h2>Kementerian Kelautan dan Perikanan</h2>
    </div>

    <div class="sub-header">
        <h3>Laporan Data Produksi KDKMP Bioflok</h3>
        <p>Periode: <strong>{{ $bulanName ?? 'Keseluruhan' }}</strong> | Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <span>Total KDKMP: <strong>{{ $totalKdmp ?? 0 }}</strong></span>
        <span>Sudah Panen: <strong style="color: #16a34a;">{{ $sudahPanen ?? 0 }}</strong></span>
        <span>Belum Panen: <strong style="color: #64748b;">{{ $belumPanen ?? 0 }}</strong></span>
        <span>Total Volume: <strong>{{ number_format($totalVolumeKg ?? 0, 0, ',', '.') }} Kg</strong></span>
        <span>Total Nilai: <strong style="color: #16a34a;">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="5%">NO</th>
                <th rowspan="2" width="35%">KDKMP</th>
                <th colspan="2" width="40%">HASIL PANEN</th>
                <th rowspan="2" width="20%">HARGA JUAL</th>
            </tr>
            <tr>
                <th width="20%">VOLUME (KG)</th>
                <th width="20%">NILAI (RP)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                @php
                    $volKg = $row['volume_kg'] ?? ($row['volume'] * 1000);
                @endphp
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td>
                        <div class="font-bold" style="color: #0f172a;">{{ $row['kdkmp'] }}</div>
                        <div style="font-size: 8px; color: #64748b; margin-top: 2px;">{{ $row['lokasi'] }}</div>
                    </td>
                    <td class="text-right">
                        @if ($volKg > 0)
                            {{ number_format($volKg, 0, ',', '.') }}
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td class="text-right text-success">
                        @if ($row['nilai'] > 0)
                            Rp {{ number_format($row['nilai'], 0, ',', '.') }}
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td class="text-right font-bold">
                        @if ($row['harga_jual'] > 0)
                            Rp {{ number_format($row['harga_jual'], 0, ',', '.') }} / Kg
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 15px; color: #64748b;">Tidak ada data produksi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if (!empty($data) && count($data) > 0)
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center font-bold">TOTAL KESELURUHAN</td>
                    <td class="text-right font-bold">{{ number_format($totalVolumeKg ?? 0, 0, ',', '.') }} Kg</td>
                    <td class="text-right text-success">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">
                        @if (($totalVolumeKg ?? 0) > 0)
                            Rp {{ number_format(round(($totalNilai ?? 0) / $totalVolumeKg), 0, ',', '.') }} / Kg
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

</body>
</html>
