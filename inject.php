<?php
$file = 'c:\laragon\www\sistem-program-prioritas-terintegrasi\resources\views\programs\knmp\dashboard\index.blade.php';
$content = file_get_contents($file);
$offcanvas = file_get_contents('c:\laragon\www\sistem-program-prioritas-terintegrasi\offcanvas_final.txt');

// find the position of @endif inside knmpMapContainer
$pos1 = strpos($content, 'id="knmpMapContainer"');
if ($pos1 !== false) {
    $pos2 = strpos($content, '@endif', $pos1);
    if ($pos2 !== false) {
        $part1 = substr($content, 0, $pos2 + 6);
        $part2 = substr($content, $pos2 + 6);
        file_put_contents($file, $part1 . PHP_EOL . $offcanvas . PHP_EOL . $part2);
        echo 'Success fallback';
    } else {
        echo 'Failed finding @endif';
    }
} else {
    echo 'Failed finding knmpMapContainer';
}