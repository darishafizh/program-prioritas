@extends('layouts.app')

@section('title', 'Program Prioritas - Dashboard Analisis Eksekutif')

@section('content')
<!-- Header & Executive Summary Narrative -->
<div class="mb-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
    <h2 class="text-xl font-semibold tracking-tight">Analisis Eksekutif <span class="text-textMain-light">{{ $activeProgram }}</span></h2>
    
    <div class="flex gap-3 shrink-0">
        <button class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm flex items-center justify-between gap-2"> Triwulan III <i class="fa-solid fa-calendar-days text-textMain-light"></i> </button>
        <button class="px-5 py-2.5 bg-teal-light hover:bg-teal-light/90 text-white rounded-md text-xs font-medium hover:shadow-lg transition-all shadow-md flex items-center justify-between gap-2"> Unduh Ringkasan <i class="fa-solid fa-file-export"></i> </button>
    </div>
</div>

<!-- Storytelling Narrative Box -->
<div class="mb-8 p-6 rounded-2xl bg-teal-light/5 dark:from-gray-900 dark:to-gray-800 border border-teal-light/20 dark:border-gray-800 shadow-sm relative overflow-hidden transition-colors duration-300">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-light/20 dark:bg-teal-light/10 rounded-full blur-3xl"></div>
    <div class="flex gap-4 relative z-10">
        <i class="fa-solid fa-quote-left text-3xl text-teal-light/30 dark:text-teal-light/20 mt-1"></i>
        <div>
            <h3 class="text-base font-medium text-textMain-light dark:text-teal-400 mb-1">Narasi Kinerja Bulan Ini</h3>
            <p class="text-sm text-textMuted-light dark:text-gray-300 leading-relaxed">
                Secara keseluruhan, {{ $activeProgram }} menunjukkan <strong class="text-textMain-light dark:text-white">tren positif dengan pencapaian fisik 64.8%</strong>. Kenaikan drastis terjadi di regional Jawa Tengah berkat akselerasi distribusi material. Namun, serapan anggaran saat ini tertahan di angka <strong class="text-warning">72%</strong>. Perhatian khusus diperlukan untuk 12 titik lokasi di Nusa Tenggara Barat yang mengalami penundaan konstruksi akibat anomali cuaca.
            </p>
        </div>
    </div>
</div>

<!-- Story Part 1: Kinerja Utama (The "What") -->
<div class="mb-4">
    <h3 class="font-medium text-base flex items-center gap-2 mb-4">
        <span class="w-8 h-8 rounded-lg bg-teal-light/20 text-textMain-light flex items-center justify-center text-sm">1</span>
        Indikator Kinerja Utama
    </h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- KPI Card 1 -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest">Total Lokasi Aktif</h4>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-teal-light bg-teal-light/10">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
            </div>
            <div class="text-base font-medium text-textMain-light dark:text-textMain-dark mb-1">3.412</div>
            <div class="flex items-center text-xs mb-3">
                <span class="text-success font-medium flex items-center"><i class="fa-solid fa-arrow-up text-[0.6rem] mr-1"></i> 12%</span>
                <span class="text-textMuted-light ml-1">dari bulan lalu</span>
            </div>
            <div class="text-[0.65rem] text-textMuted-light bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border-l-2 border-teal-light">
                <strong>Insight:</strong> Ekspansi tertinggi terjadi di wilayah pesisir utara Jawa.
            </div>
        </div>

        <!-- KPI Card 2 -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest">Akumulasi Produksi</h4>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-success bg-success/10">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="text-base font-medium text-textMain-light dark:text-textMain-dark mb-1">85.4 <span class="text-sm font-medium">Ribu Ton</span></div>
            <div class="flex items-center text-xs mb-3">
                <span class="text-success font-medium flex items-center"><i class="fa-solid fa-arrow-up text-[0.6rem] mr-1"></i> 8.4%</span>
                <span class="text-textMuted-light ml-1">dari bulan lalu</span>
            </div>
            <div class="text-[0.65rem] text-textMuted-light bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border-l-2 border-success">
                <strong>Insight:</strong> Melebihi target Q3 sebesar 5%. Puncak panen terjadi minggu ini.
            </div>
        </div>

        <!-- KPI Card 3 -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest">Progres Fisik</h4>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-warning bg-warning/10">
                    <i class="fa-solid fa-person-digging"></i>
                </div>
            </div>
            <div class="text-base font-medium text-textMain-light dark:text-textMain-dark mb-1">64.8%</div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mb-3">
                <div class="bg-warning h-full rounded-full" style="width: 64.8%"></div>
            </div>
            <div class="text-[0.65rem] text-textMuted-light bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border-l-2 border-warning">
                <strong>Deviasi:</strong> <span class="text-danger font-medium">-2.1%</span> dari Kurva S target rencana.
            </div>
        </div>

        <!-- KPI Card 4 -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest">Serapan Anggaran</h4>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-textMain-light bg-teal-light/10">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
            </div>
            <div class="text-base font-medium text-textMain-light dark:text-textMain-dark mb-1">4.2 <span class="text-sm font-medium">Triliun</span></div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mb-3">
                <div class="bg-teal-light h-full rounded-full" style="width: 72%"></div>
            </div>
            <div class="text-[0.65rem] text-textMuted-light bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border-l-2 border-teal-light">
                <strong>Kesehatan:</strong> Serapan berjalan sinkron dengan progres fisik (selisih <10%).
            </div>
        </div>
    </div>
</div>

<!-- Story Part 2: Analisis Akar Masalah & Tren (The "Why") -->
<div class="mb-4">
    <h3 class="font-medium text-base flex items-center gap-2 mb-4">
        <span class="w-8 h-8 rounded-lg bg-teal-light/20 text-teal-light flex items-center justify-center text-sm">2</span>
        Analisis Kesenjangan & Tren
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Gap Analysis Chart (Simulated) -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 lg:col-span-2 p-6 flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h4 class="font-medium text-md">Komparasi: Target vs Realisasi Regional</h4>
                    <p class="text-xs text-textMuted-light">Kesenjangan terbesar diukur berdasarkan wilayah untuk prioritas intervensi.</p>
                </div>
                <button class="text-xs font-medium text-textMain-light hover:underline">Unduh Data</button>
            </div>
            
            <div class="flex-1 flex flex-col justify-center space-y-5 py-4">
                <!-- Bar 1 -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span>Jawa Timur (Over-performing)</span>
                        <span class="text-success">+14% dari Target</span>
                    </div>
                    <div class="relative w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="absolute left-0 top-0 h-full bg-gray-300 dark:bg-gray-600 rounded-full" style="width: 75%"></div> <!-- Target -->
                        <div class="absolute left-0 top-0 h-full bg-success opacity-80 rounded-full" style="width: 89%"></div> <!-- Realisasi -->
                    </div>
                </div>

                <!-- Bar 2 -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span>Jawa Tengah (On-Track)</span>
                        <span class="text-textMain-light">+2% dari Target</span>
                    </div>
                    <div class="relative w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="absolute left-0 top-0 h-full bg-gray-300 dark:bg-gray-600 rounded-full" style="width: 80%"></div>
                        <div class="absolute left-0 top-0 h-full bg-teal-light opacity-80 rounded-full" style="width: 82%"></div>
                    </div>
                </div>

                <!-- Bar 3 -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span>Nusa Tenggara Barat (Under-performing)</span>
                        <span class="text-danger">-18% dari Target</span>
                    </div>
                    <div class="relative w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="absolute left-0 top-0 h-full bg-gray-300 dark:bg-gray-600 rounded-full" style="width: 65%"></div>
                        <div class="absolute left-0 top-0 h-full bg-danger opacity-80 rounded-full z-10" style="width: 47%"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-2 text-[0.7rem] flex items-center justify-center gap-4 text-textMuted-light font-medium">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-gray-300 dark:bg-gray-600"></span> Target Rencana</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-teal-light"></span> Realisasi Lapangan</span>
            </div>
        </div>

        <!-- Root Cause Analysis List -->
        <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col">
            <h4 class="font-medium text-md mb-1">Faktor Penghambat Utama</h4>
            <p class="text-xs text-textMuted-light mb-5">Distribusi masalah terbanyak berdasar log harian.</p>
            
            <div class="space-y-4 flex-1">
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-danger/10 text-danger flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cloud-showers-heavy text-xs"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium">Cuaca Buruk (45%)</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Menghambat suplai material di NTB dan NTT. 34 proyek tertunda.</div>
                    </div>
                </div>
                
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium">Keterlambatan Vendor (30%)</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Vendor penyedia bibit di Jawa Barat terlambat 2 minggu dari jadwal.</div>
                    </div>
                </div>

                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-teal-light/10 text-teal-light flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-signature text-xs"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium">Administrasi Lahan (15%)</div>
                        <div class="text-xs text-textMuted-light mt-0.5">Isu pembebasan lahan di 12 titik pesisir Sulawesi.</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Story Part 3: Tindak Lanjut (The "What's Next") -->
<div class="mb-12">
    <h3 class="font-medium text-base flex items-center gap-2 mb-4">
        <span class="w-8 h-8 rounded-lg bg-navy-light dark:bg-blue-600 text-white flex items-center justify-center text-sm">3</span>
        Rekomendasi & Rencana Tindakan
    </h3>
    
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 text-textMuted-light dark:text-textMuted-dark text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-medium">Prioritas Aksi</th>
                    <th class="px-6 py-4 font-medium">Target Wilayah</th>
                    <th class="px-6 py-4 font-medium">PIC / Tanggung Jawab</th>
                    <th class="px-6 py-4 font-medium">Tenggat Waktu</th>
                    <th class="px-6 py-4 font-medium text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-danger flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i> Relokasi Anggaran Darurat Cuaca
                        </div>
                        <div class="text-xs text-textMuted-light mt-1 w-64 line-clamp-2">Suntikan dana operasional ekstra untuk menutup delay logistik akibat cuaca buruk.</div>
                    </td>
                    <td class="px-6 py-4 font-medium">NTB & NTT</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-navy-light text-white flex items-center justify-center text-[0.6rem] font-medium">DI</div>
                            <span class="font-medium">Direktorat Investasi</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">12 Sep 2026</td>
                    <td class="px-6 py-4 text-right">
                        <button class="px-4 py-1.5 rounded-md bg-danger/10 text-danger font-medium text-xs hover:bg-danger hover:text-white transition-colors">Eksekusi</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-textMain-light dark:text-textMain-dark">
                            Pemberian Surat Peringatan (SP1) Vendor
                        </div>
                        <div class="text-xs text-textMuted-light mt-1 w-64 line-clamp-2">Audit dan teguran pada supplier bibit CV Alam Lestari atas keterlambatan 2 minggu.</div>
                    </td>
                    <td class="px-6 py-4 font-medium">Jawa Barat</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-warning text-white flex items-center justify-center text-[0.6rem] font-medium">PP</div>
                            <span class="font-medium">PPK Pengadaan</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-warning">15 Sep 2026</td>
                    <td class="px-6 py-4 text-right">
                        <button class="px-4 py-1.5 rounded-md bg-gray-100 dark:bg-gray-800 font-medium text-xs hover:bg-teal-light hover:text-white transition-colors">Tindak Lanjut</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-textMain-light dark:text-textMain-dark">
                            Validasi Akselerasi Progres 
                        </div>
                        <div class="text-xs text-textMuted-light mt-1 w-64 line-clamp-2">Pemberian insentif termin lebih awal untuk mendorong performa kelompok tani yang sudah On-Track.</div>
                    </td>
                    <td class="px-6 py-4 font-medium">Jawa Tengah, Jatim</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-success text-white flex items-center justify-center text-[0.6rem] font-medium">EV</div>
                            <span class="font-medium">Tim Evaluasi Pusat</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-textMuted-light">20 Sep 2026</td>
                    <td class="px-6 py-4 text-right">
                        <button class="px-4 py-1.5 rounded-md bg-gray-100 dark:bg-gray-800 font-medium text-xs hover:bg-teal-light hover:text-white transition-colors">Tindak Lanjut</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection











