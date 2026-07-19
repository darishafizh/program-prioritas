<?php
$file = 'c:\laragon\www\sistem-program-prioritas-terintegrasi\resources\views\programs\knmp\dashboard\index.blade.php';
$content = file_get_contents($file);
$startMarker = '<!-- OFFCANVAS DETAIL 75% LEBAR LAYAR (Muncul dari kanan saat titik lokasi diklik) -->';
$startPos = strpos($content, $startMarker);
if ($startPos === false) { echo "Start not found.\n"; exit; }
$endPos = strpos($content, '    </template>', $startPos);
if ($endPos === false) { echo "End not found.\n"; exit; }
$endPos += strlen('    </template>');
$part1 = substr($content, 0, $startPos);
$part2 = substr($content, $endPos);
$newOffcanvas = file_get_contents('c:\laragon\www\sistem-program-prioritas-terintegrasi\offcanvas_final.txt');
file_put_contents($file, $part1 . $newOffcanvas . $part2);
echo "OK\n";