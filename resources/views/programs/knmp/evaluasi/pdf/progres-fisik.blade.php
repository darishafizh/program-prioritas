<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Progres Fisik KNMP</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; text-transform: uppercase; }
        .sub-header { text-align: center; margin-bottom: 10px; }
        .sub-header h3 { margin: 0; font-size: 12px; text-transform: uppercase; }
        .sub-header p { margin: 2px 0; font-size: 10px; }
        .rata-rata { text-align: right; margin-bottom: 5px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #ddd; padding: 6px 5px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; text-align: center; font-weight: bold; font-size: 9px; }
        .text-center { text-align: center; }
        .text-danger { color: #ef4444; }
        .text-success { color: #22c55e; }
        .text-warning { color: #d97706; }
        .font-bold { font-weight: bold; }
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
        <h3>Evaluasi Progres Fisik Konstruksi KNMP</h3>
        <p>Batch: {{ $batchName }} | Data per tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    @php
        $totalProgres = 0;
        $count = 0;
        foreach ($data as $row) {
            if ($row['progres'] > 0) { $totalProgres += $row['progres']; $count++; }
        }
        $avgProgres = $count > 0 ? round($totalProgres / $count, 1) : 0;
    @endphp

    <div class="rata-rata">
        Rata-rata Progres: <span class="font-bold">{{ $avgProgres }}%</span> | 
        Total Lokasi: <span class="font-bold">{{ count($data) }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="28%">NAMA KNMP</th>
                <th width="20%">PENYEDIA JASA KONSTRUKSI</th>
                <th width="10%">RENCANA<br>(%)</th>
                <th width="10%">PROGRES<br>(%)</th>
                <th width="10%">DEVIASI<br>(%)</th>
                <th width="10%">STATUS</th>
                <th width="7%">TAHAP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $row['nama'] }}</td>
                <td>{{ $row['konstruktor'] }}</td>
                <td class="text-center font-bold">{{ str_replace('.', ',', $row['rencana']) }}</td>
                <td class="text-center font-bold">{{ str_replace('.', ',', $row['progres']) }}</td>
                <td class="text-center">
                    @if($row['deviasi'] < 0)
                        <span class="text-danger font-bold">{{ str_replace('.', ',', $row['deviasi']) }}</span>
                    @else
                        <span class="text-success font-bold">+{{ str_replace('.', ',', $row['deviasi']) }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($row['deviasi'] < -5)
                        <span class="text-danger font-bold">Kritis</span>
                    @elseif($row['deviasi'] < 0)
                        <span class="text-warning font-bold">Terlambat</span>
                    @else
                        <span class="text-success font-bold">Sesuai</span>
                    @endif
                </td>
                <td class="text-center">{{ $row['tahap'] === 'serah_terima' ? 'Selesai' : 'Aktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
