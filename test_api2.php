<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiData = \Illuminate\Support\Facades\Cache::get('knmp_api_data');
if (!is_array($apiData)) {
    echo "Fetching API data...\n";
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->timeout(15)
        ->get('https://kdmp.pdspkp.id/knmp/get_data.php');
    if ($response->successful()) {
        $apiData = $response->json();
        \Illuminate\Support\Facades\Cache::put('knmp_api_data', $apiData, 3600);
    }
}
echo "API Data Count: " . count($apiData) . "\n";

$apiKeys = [
    'SPBN' => 'SPBUN_status',
    'Docking' => 'Docking nelayan_status',
    'Bengkel' => 'Bengkel Nelayan_status',
    'Waserda' => 'Waserda_status',
    'Pabrik Es' => 'Pabrik Es_status',
    'Cold Storage' => 'Cold Storage_status',
    'KDRN Dingin' => 'Kenderaan Berpendingin_status',
    'Sentra Kuliner' => 'Sentra Kuliner_status',
    'Kios Pemasaran' => 'Kios Pemasaran_status',
    'Kapal' => 'Kapal_status',
    'Mesin' => 'Mesin_Status',
    'Alat Tangkap' => 'Alat_tangkap_Status',
    'Cool Box' => 'cool_box_status',
    'Roda 3' => 'roda3_status',
];

$masterSarpras = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('master_sarpras')->get();
foreach ($masterSarpras as $s) {
    $tersedia = 0;
    $target = 0;
    $apiKey = $apiKeys[$s->nama] ?? null;
    echo "S: {$s->nama}, API Key: {$apiKey}\n";
    if ($apiKey && is_array($apiData)) {
        foreach ($apiData as $item) {
            if (isset($item[$apiKey])) {
                $val = $item[$apiKey];
                if (stripos($val, '2. Sudah Operasional') !== false) {
                    $tersedia++;
                    $target++;
                } elseif (stripos($val, '1. Belum Operasional') !== false) {
                    $target++;
                }
            }
        }
    }
    echo "  Tersedia: $tersedia, Target: $target\n";
}
