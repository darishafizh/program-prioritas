<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->timeout(15)
        ->get('https://kdmp.pdspkp.id/knmp/get_data.php');
    if ($response->successful()) {
        $json = $response->json();
        if ($json === null) {
            echo "json() returned NULL! JSON Error: " . json_last_error_msg() . "\n";
        } else {
            echo "json() parsed correctly, count: " . count($json) . "\n";
        }
    }
} catch (\Exception $e) {
    echo 'EXCEPTION: ' . $e->getMessage() . "\n";
}
