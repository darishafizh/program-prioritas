<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Progres Pembangunan KNMP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .sub-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .sub-header h3 {
            margin: 0;
            font-size: 12px;
            text-transform: uppercase;
        }
        .sub-header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .rata-rata {
            text-align: right;
            margin-bottom: 5px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        .text-center { text-align: center; }
        .text-danger { color: #ef4444; }
        .text-success { color: #22c55e; }
        .font-bold { font-weight: bold; }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Grid Dokumentasi */
        .grid-dokumentasi {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px;
            margin-top: 20px;
        }
        .dok-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            width: 33.33%;
            padding: 10px;
            box-sizing: border-box;
        }
        .dok-img-wrapper {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }
        .dok-img {
            height: 120px;
            max-width: 100%;
            width: auto;
        }
        .dok-title {
            font-weight: bold;
            font-size: 11px;
            margin: 0 0 3px 0;
        }
        .dok-subtitle {
            font-size: 9px;
            color: #64748b;
            margin: 0 0 3px 0;
        }
        .dok-progres {
            font-size: 10px;
            color: #333;
            margin: 0;
        }
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
        <h3>Progres Pembangunan KNMP Tahap II</h3>
        <p>Data per tanggal {{ $tanggal }}</p>
    </div>

    <div class="rata-rata">
        Rata-rata Progres Nasional: <span class="font-bold">{{ $avgProgres }}%</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="35%">NAMA KNMP DAN LOKASI</th>
                <th width="20%">PENYEDIA JASA KONSTRUKSI</th>
                <th width="10%">RENCANA<br>(%)</th>
                <th width="10%">PROGRES<br>(%)</th>
                <th width="10%">STATUS</th>
                <th width="10%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $row['lokasi'] }}</div>
                    <div style="font-size: 8px; color: #666; margin-top:2px;">{{ $row['daerah'] }}</div>
                </td>
                <td>{{ $row['konstruktor'] }}</td>
                <td class="text-center font-bold">{{ str_replace('.', ',', $row['rencana']) }}</td>
                <td class="text-center font-bold">{{ str_replace('.', ',', $row['progres']) }}</td>
                <td class="text-center">
                    @if($row['deviasi'] < 0)
                        <div class="text-danger font-bold" style="font-size: 9px;">Underperform</div>
                        <div class="text-danger" style="font-size: 8px;">{{ str_replace('.', ',', $row['deviasi']) }}%</div>
                    @else
                        <div class="text-success font-bold" style="font-size: 9px;">On Track</div>
                        <div class="text-success" style="font-size: 8px;">+{{ str_replace('.', ',', $row['deviasi']) }}%</div>
                    @endif
                </td>
                <td class="text-center">-</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="sub-header" style="margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <h3>Dokumentasi Progres Pembangunan KNMP</h3>
    </div>

    <!-- Render dokumentasi dalam format tabel grid (3 kolom) untuk kompatibilitas DOMPDF -->
    <table class="grid-dokumentasi">
        <tbody>
            @php $count = 0; @endphp
            <tr>
            @foreach($data as $row)
                @if($count > 0 && $count % 3 == 0)
                    </tr><tr>
                @endif
                <td class="dok-card">
                    <div class="dok-img-wrapper">
                        @php
                            $fotoPath = null;
                            if ($row['foto']) {
                                if (file_exists(public_path('storage/' . $row['foto']))) {
                                    $fotoPath = public_path('storage/' . $row['foto']);
                                } elseif (file_exists(storage_path('app/public/' . $row['foto']))) {
                                    $fotoPath = storage_path('app/public/' . $row['foto']);
                                }
                            }
                        @endphp
                        @if($fotoPath)
                            @php
                                $src = '';
                                if (file_exists($fotoPath)) {
                                    $type = strtolower(pathinfo($fotoPath, PATHINFO_EXTENSION));
                                    // DOMPDF struggles with some PNGs (e.g. interlaced). Convert all images safely if GD is available.
                                    if (extension_loaded('gd')) {
                                        $imgDataRaw = file_get_contents($fotoPath);
                                        $gdImage = @imagecreatefromstring($imgDataRaw);
                                        if ($gdImage) {
                                            // Convert to white background
                                            $bg = imagecreatetruecolor(imagesx($gdImage), imagesy($gdImage));
                                            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                                            imagecopy($bg, $gdImage, 0, 0, 0, 0, imagesx($gdImage), imagesy($gdImage));
                                            
                                            ob_start();
                                            imagejpeg($bg, null, 85);
                                            $safeData = ob_get_clean();
                                            $src = 'data:image/jpeg;base64,' . base64_encode($safeData);
                                            
                                            imagedestroy($bg);
                                            imagedestroy($gdImage);
                                        } else {
                                            if ($type === 'jpg') $type = 'jpeg';
                                            $src = 'data:image/' . $type . ';base64,' . base64_encode($imgDataRaw);
                                        }
                                    } else {
                                        if ($type === 'jpg') $type = 'jpeg';
                                        $src = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($fotoPath));
                                    }
                                }
                            @endphp
                            <img src="{{ $src }}" class="dok-img" />
                        @else
                            <div style="padding-top: 50px; color: #ccc;">No Image</div>
                        @endif
                    </div>
                    <p class="dok-title">{{ $row['lokasi'] }}</p>
                    <p class="dok-subtitle">{{ $row['daerah'] }}</p>
                    <p class="dok-progres">Progres: {{ str_replace('.', ',', $row['progres']) }}%</p>
                </td>
                @php $count++; @endphp
            @endforeach
            
            {{-- Isi sisa td kosong jika kolom terakhir tidak penuh --}}
            @while($count % 3 != 0)
                <td style="border: none;"></td>
                @php $count++; @endphp
            @endwhile
            </tr>
        </tbody>
    </table>

</body>
</html>
