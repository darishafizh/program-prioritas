<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Calon Lokasi KNMP</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; text-transform: uppercase; }
        .sub-header { text-align: center; margin-bottom: 10px; }
        .sub-header h3 { margin: 0; font-size: 12px; text-transform: uppercase; }
        .sub-header p { margin: 2px 0; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #ddd; padding: 6px 5px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; text-align: center; font-weight: bold; font-size: 9px; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-pengajuan { background-color: #dbeafe; color: #2563eb; }
        .badge-verifikasi { background-color: #ccfbf1; color: #0d9488; }
        .badge-penetapan { background-color: #dcfce7; color: #16a34a; }
        .badge-ditolak { background-color: #fecaca; color: #dc2626; }
        .stage-done { color: #16a34a; font-weight: bold; }
        .stage-active { color: #0d9488; font-weight: bold; }
        .stage-pending { color: #9ca3af; }
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
        <h3>Evaluasi Pipeline Calon Lokasi KNMP</h3>
        <p>Batch: {{ $batchName }} | Data per tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        <p>Total Calon Lokasi: <strong>{{ count($data) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">NAMA LOKASI</th>
                <th width="15%">WILAYAH</th>
                <th width="15%">PENGUSUL</th>
                <th width="15%">STATUS</th>
                <th width="15%">TAHAP KE-</th>
                <th width="10%">TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $row['nama_lokasi'] }}</td>
                <td>{{ $row['kabupaten'] }}, {{ $row['provinsi'] }}</td>
                <td>{{ $row['pengusul'] }}</td>
                <td class="text-center">
                    @php
                        $statusLabels = [
                            'pengajuan' => 'Pengajuan', 'verif_admin' => 'Verif Admin',
                            'ba_aktivasi' => 'BA Aktivasi', 'verif_teknis' => 'Verif Teknis',
                            'ba_calon' => 'BA Calon', 'penetapan' => 'Penetapan', 'ditolak' => 'Ditolak',
                        ];
                    @endphp
                    <span class="badge {{ $row['status_tahapan'] === 'pengajuan' ? 'badge-pengajuan' : ($row['status_tahapan'] === 'penetapan' ? 'badge-penetapan' : ($row['status_tahapan'] === 'ditolak' ? 'badge-ditolak' : 'badge-verifikasi')) }}">
                        {{ $statusLabels[$row['status_tahapan']] ?? $row['status_tahapan'] }}
                    </span>
                </td>
                <td class="text-center font-bold">{{ ($row['stage_index'] + 1) }} / {{ $row['total_stages'] }}</td>
                <td class="text-center">{{ $row['created_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
