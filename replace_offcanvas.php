<?php
$file = 'c:\laragon\www\sistem-program-prioritas-terintegrasi\resources\views\programs\knmp\dashboard\index.blade.php';
$content = file_get_contents($file);

$startMarker = '<!-- OFFCANVAS DETAIL 75% LEBAR LAYAR (Muncul dari kanan saat titik lokasi diklik) -->';
$startPos = strpos($content, $startMarker);

if ($startPos === false) {
    echo "Could not find start marker.\n";
    exit;
}

// The offcanvas ends with </template> right before the final </div> (actually it ends with     </template>).
// Let's find the closing     </template> after $startPos
$endPos = strpos($content, '    </template>', $startPos);

if ($endPos === false) {
    echo "Could not find end marker.\n";
    exit;
}

$endPos += strlen('    </template>');

$part1 = substr($content, 0, $startPos);
$part2 = substr($content, $endPos);

$newOffcanvas = file_get_contents('c:\laragon\www\sistem-program-prioritas-terintegrasi\offcanvas_reconstruct.txt');

file_put_contents($file, $part1 . $newOffcanvas . $part2);
echo "Successfully replaced offcanvas.\n";