<?php

return [
    'knmp' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'items' => [
                [
                    'label' => 'Siklus & Operasional',
                    'icon' => 'fa-arrows-spin',
                    'url' => '/dashboard/knmp/siklus',
                ],
                [
                    'label' => 'Progres Fisik',
                    'icon' => 'fa-person-digging',
                    'url' => '/dashboard/knmp',
                ]
            ]
        ],
        'Master Data' => [
            'heading' => 'Manajemen Data',
            'items' => [
                [
                    'label' => 'Tahap (Batch)',
                    'icon' => 'fa-layer-group',
                    'url' => '/master/knmp/batch',
                ],
                [
                    'label' => 'Kriteria Lokasi',
                    'icon' => 'fa-list-check',
                    'url' => '/master/knmp/kriteria-lokasi',
                ],
                [
                    'label' => 'Data Vendor/Penyedia',
                    'icon' => 'fa-building-user',
                    'url' => '/master/knmp/vendor',
                ],
                [
                    'label' => 'Calon Lokasi',
                    'icon' => 'fa-map-location-dot',
                    'url' => '/master/knmp',
                ]
            ]
        ],
        'Operasional' => [
            'heading' => 'Eksekusi Proyek',
            'items' => [
                [
                    'label' => 'Pelaksanaan Proyek',
                    'icon' => 'fa-helmet-safety',
                    'url' => '/operasional/knmp',
                ]
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                [
                    'label' => 'Calon Lokasi',
                    'icon' => 'fa-map-location-dot',
                    'url' => '/evaluasi/knmp/calon-lokasi',
                ],
                [
                    'label' => 'Operasional Proyek',
                    'icon' => 'fa-helmet-safety',
                    'url' => '/evaluasi/knmp/operasional',
                ],
                [
                    'label' => 'Progres Fisik',
                    'icon' => 'fa-person-digging',
                    'url' => '/evaluasi/knmp/progres-fisik',
                ]
            ]
        ]
    ],
    'bioflok' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'items' => [
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/dashboard/bioflok'],
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '#']
            ]
        ],
        'Master Data' => [
            'heading' => 'Referensi Program',
            'items' => [
                ['label' => 'KDMP', 'icon' => 'fa-water', 'url' => '/master/bioflok'],
                ['label' => 'SPPG', 'icon' => 'fa-fish', 'url' => '#']
            ]
        ],
        'Operasional' => [
            'heading' => 'Manajemen Data',
            'items' => [
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/operasional/bioflok'],
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '#']
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/evaluasi/bioflok'],
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '#']
            ]
        ]
    ],
    'bins' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'items' => [
                ['label' => 'Dashboard Utama', 'icon' => 'fa-chart-line', 'url' => '/dashboard/bins']
            ]
        ],
        'Master Data' => [
            'heading' => 'Referensi Program',
            'items' => [
                ['label' => 'Petak', 'icon' => 'fa-draw-polygon', 'url' => '/master/bins?type=petak'],
                ['label' => 'Kolam', 'icon' => 'fa-water', 'url' => '/master/bins?type=kolam']
            ]
        ],
        'Operasional' => [
            'heading' => 'Manajemen Data',
            'items' => [
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '/operasional/bins']
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '/evaluasi/bins']
            ]
        ]
    ],
    'default' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'items' => [
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '#'],
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '#']
            ]
        ],
        'Master Data' => [
            'heading' => 'Referensi Program',
            'items' => [
                ['label' => 'Kategori Indikator', 'icon' => 'fa-tags', 'url' => '#'],
                ['label' => 'Penyedia / Vendor', 'icon' => 'fa-users-gear', 'url' => '#'],
                ['label' => 'Wilayah Tugas', 'icon' => 'fa-map-pin', 'url' => '#']
            ]
        ],
        'Operasional' => [
            'heading' => 'Manajemen Data',
            'items' => [
                ['label' => 'Data Lokasi / Titik', 'icon' => 'fa-location-dot', 'url' => '#'],
                ['label' => 'Input Progres Fisik', 'icon' => 'fa-clipboard-list', 'url' => '#'],
                ['label' => 'Input Data Produksi', 'icon' => 'fa-truck-ramp-box', 'url' => '#']
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                ['label' => 'Ekspor Laporan', 'icon' => 'fa-file-pdf', 'url' => '#'],
                ['label' => 'Validasi Evaluasi', 'icon' => 'fa-check-double', 'url' => '#']
            ]
        ]
    ]
];
