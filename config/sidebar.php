<?php

return [
    'knmp' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'url' => '/dashboard/knmp',
            'active' => ['dashboard/knmp', 'dashboard/knmp/konstruksi*', 'dashboard/knmp/operasional*', 'dashboard/knmp/siklus*']
        ],
        'Master Data' => [
            'heading' => 'Manajemen Data',
            'items' => [
                [
                    'label' => 'Tahap',
                    'icon' => 'fa-layer-group',
                    'url' => '/master/knmp/tahap',
                    'active' => ['master/knmp/tahap*']
                ],

                [
                    'label' => 'Vendor',
                    'icon' => 'fa-building-user',
                    'url' => '/master/knmp/vendor',
                    'active' => ['master/knmp/vendor*']
                ]
            ]
        ],
        'Operasional' => [
            'heading' => 'Eksekusi Proyek',
            'items' => [
                [
                    'label' => 'Pengajuan',
                    'icon' => 'fa-map-location-dot',
                    'url' => '/master/knmp/calon-lokasi',
                    'active' => ['master/knmp/calon-lokasi*']
                ],
                [
                    'label' => 'Pelaksanaan',
                    'icon' => 'fa-helmet-safety',
                    'url' => '/operasional/knmp',
                ]
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                [
                    'label' => 'Pengajuan',
                    'icon' => 'fa-map-location-dot',
                    'url' => '/evaluasi/knmp/calon-lokasi',
                ],
                [
                    'label' => 'Pelaksanaan',
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
    'budidaya-tematik' => [
        'Dashboard' => [
            'heading' => 'Ringkasan Eksekutif',
            'items' => [
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '/dashboard/budidaya-tematik/produksi', 'active' => ['dashboard/bioflok/produksi*', 'dashboard/budidaya-tematik/produksi*']],
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/dashboard/budidaya-tematik/progres-fisik']
            ]
        ],
        'Master Data' => [
            'heading' => 'Referensi Program',
            'items' => [
                ['label' => 'KDKMP', 'icon' => 'fa-water', 'url' => '/master/budidaya-tematik/kdkmp', 'active' => ['master/bioflok*', 'master/budidaya-tematik*']],
                ['label' => 'SPPG', 'icon' => 'fa-fish', 'url' => '#']
            ]
        ],
        'Operasional' => [
            'heading' => 'Manajemen Data',
            'items' => [
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '/operasional/budidaya-tematik/produksi', 'active' => ['operasional/bioflok/produksi*', 'operasional/budidaya-tematik/produksi*']],
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/operasional/budidaya-tematik/progres-fisik']
            ]
        ],
        'Evaluasi' => [
            'heading' => 'Pelaporan & Audit',
            'items' => [
                ['label' => 'Produksi', 'icon' => 'fa-boxes-stacked', 'url' => '/evaluasi/budidaya-tematik/produksi', 'active' => ['evaluasi/bioflok/produksi*', 'evaluasi/budidaya-tematik/produksi*']],
                ['label' => 'Progres Fisik', 'icon' => 'fa-person-digging', 'url' => '/evaluasi/budidaya-tematik/progres-fisik']
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
