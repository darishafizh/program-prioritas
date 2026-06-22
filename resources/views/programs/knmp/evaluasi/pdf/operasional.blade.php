<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Operasional Proyek KNMP</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; text-transform: uppercase; }
        .sub-header { text-align: center; margin-bottom: 15px; }
        .sub-header h3 { margin: 0; font-size: 12px; text-transform: uppercase; }
        .sub-header p { margin: 2px 0; font-size: 10px; }
        .summary { margin-bottom: 15px; text-align: center; }
        .summary span { display: inline-block; margin: 0 8px; padding: 3px 10px; background-color: #f1f5f9; border-radius: 4px; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #ddd; padding: 6px 5px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; text-align: center; font-weight: bold; font-size: 9px; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-usulan { background-color: #dbeafe; color: #2563eb; }
        .badge-survey { background-color: #ccfbf1; color: #0d9488; }
        .badge-ded { background-color: #ede9fe; color: #7c3aed; }
        .badge-lelang { background-color: #fef3c7; color: #d97706; }
        .badge-konstruksi { background-color: #ffedd5; color: #ea580c; }
        .badge-serah_terima { background-color: #dcfce7; color: #16a34a; }
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
        <h3>Evaluasi Operasional Proyek KNMP</h3>
        <p>Batch: {{ $batchName }} | Data per tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <div class="summary">
        @php
            $stageLabels = ['usulan' => 'Usulan', 'survey' => 'Survei', 'ded' => 'DED', 'lelang' => 'Lelang', 'konstruksi' => 'Konstruksi', 'serah_terima' => 'Serah Terima'];
        @endphp
        @foreach($stats as $key => $count)
            <span>{{ $stageLabels[$key] ?? $key }}: <strong>{{ $count }}</strong></span>
        @endforeach
        <span>Total: <strong>{{ count($data) }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">NAMA KNMP</th>
                <th width="20%">WILAYAH</th>
                <th width="10%">TIPE</th>
                <th width="15%">TAHAP SAAT INI</th>
                <th width="10%">TAHAP KE-</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $row['nama'] }}</td>
                <td>{{ $row['kabupaten'] }}, {{ $row['provinsi'] }}</td>
                <td class="text-center">{{ $row['status'] }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $row['tahap_saat_ini'] }}">
                        {{ $stageLabels[$row['tahap_saat_ini']] ?? $row['tahap_saat_ini'] }}
                    </span>
                </td>
                <td class="text-center font-bold">{{ ($row['stage_index'] + 1) }} / {{ $row['total_stages'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
