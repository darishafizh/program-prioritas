<?php
$file = 'c:\laragon\www\sistem-program-prioritas-terintegrasi\resources\views\programs\knmp\dashboard\index.blade.php';
$content = file_get_contents($file);

// First, remove the wrongly injected offcanvas
$offcanvasStart = '<!-- OFFCANVAS DETAIL 75% LEBAR LAYAR (Muncul dari kanan saat titik lokasi diklik) -->';
$offcanvasEnd = '</div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>';

$startPos = strpos($content, $offcanvasStart);
if ($startPos !== false) {
    // Find the end of the offcanvas
    $endPos = strpos($content, '    </div>', $startPos + 5000);
    if ($endPos !== false) {
        $part1 = substr($content, 0, $startPos);
        $part2 = substr($content, $endPos + 10); // +10 to consume '    </div>'
        $content = $part1 . $part2;
    }
}

// Now re-inject it properly.
// The structure is:
// <div id="knmpMapContainer" ...>
//    @if (...)
//       ...
//    @else
//       ...
//    @endif
// </div>
// Let's find the closing </div> of knmpMapContainer. It's right before:
// <div class="grid gap-3 sm:gap-4 p-6 bg-gray-50/50

$targetStr = '<div class="grid gap-3 sm:gap-4 p-6 bg-gray-50/50';
$targetPos = strpos($content, $targetStr);
if ($targetPos !== false) {
    // We need to find the </div> just before $targetStr
    $part1 = substr($content, 0, $targetPos);
    $part2 = substr($content, $targetPos);
    
    // Reverse search for </div> in part1
    $lastDivPos = strrpos($part1, '</div>');
    if ($lastDivPos !== false) {
        $beforeDiv = substr($part1, 0, $lastDivPos);
        $afterDiv = substr($part1, $lastDivPos); // This should be '</div>...'
        
        $offcanvas = file_get_contents('c:\laragon\www\sistem-program-prioritas-terintegrasi\offcanvas_final.txt');
        
        $newContent = $beforeDiv . PHP_EOL . $offcanvas . PHP_EOL . $afterDiv . $part2;
        file_put_contents($file, $newContent);
        echo 'Success re-inject';
    } else {
        echo 'Failed finding last div';
    }
} else {
    echo 'Failed finding grid';
}