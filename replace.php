<?php

$models = [
    "CalonLokasi",
    "CalonLokasiBaAktivasi",
    "CalonLokasiBaCalon",
    "CalonLokasiDetail",
    "CalonLokasiPenetapan",
    "CalonLokasiPengajuan",
    "CalonLokasiVerifAdmin",
    "CalonLokasiVerifTeknis",
    "KnmpProyek",
    "KonstruksiKnmp",
    "PenyediaJasaKonstruksi",
    "TahapDed",
    "TahapKonstruksi",
    "TahapLelang",
    "TahapSerahTerima",
    "TahapSurvey",
    "TahapUsulan",
];

$directories = ['app', 'database', 'routes', 'resources/views'];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (!in_array($ext, ['php'])) continue;

    // Skip vendor and other non-essential dirs
    $skip = false;
    foreach (['vendor', 'node_modules', 'storage', 'bootstrap', '.git', 'test_ssl.php', 'replace.php'] as $ignore) {
        if (strpos($path, DIRECTORY_SEPARATOR . $ignore . DIRECTORY_SEPARATOR) !== false || strpos($path, $ignore) !== false) {
            $skip = true;
            break;
        }
    }
    if ($skip) continue;

    $content = file_get_contents($path);
    $original = $content;

    foreach ($models as $model) {
        // use App\Models\ModelName; -> use App\Models\Knmp\ModelName;
        $content = str_replace("use App\Models\\$model;", "use App\Models\Knmp\\$model;", $content);
        
        // \App\Models\ModelName:: -> \App\Models\Knmp\ModelName::
        $content = str_replace("\App\Models\\$model::", "\App\Models\Knmp\\$model::", $content);
        
        // App\Models\ModelName (general cases in strings or arrays)
        $content = str_replace("'App\\\\Models\\\\$model'", "'App\\\\Models\\\\Knmp\\\\$model'", $content);
        $content = str_replace("\"App\\\\Models\\\\$model\"", "\"App\\\\Models\\\\Knmp\\\\$model\"", $content);
    }

    if ($original !== $content) {
        file_put_contents($path, $content);
        echo "Updated references in: " . str_replace(__DIR__, '', $path) . "\n";
    }
}

echo "Refactoring complete.\n";
