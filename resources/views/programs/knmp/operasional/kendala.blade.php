@extends('layouts.app')

@section('title', 'KNMP - Log Kendala Lapangan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">Log <span class="text-info dark:text-blue-400">Kendala Lapangan</span></h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm mt-1">Jurnal pemantauan masalah, hambatan, dan solusi selama proses konstruksi KNMP.</p>
    </div>

    <div class="flex items-center gap-3">
        <button class="bg-gradient-to-r from-warning to-amber-600 hover:from-amber-600 hover:to-warning text-white rounded-xl px-4 py-2 text-sm font-bold shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-bullhorn"></i> Lapor Kendala Baru
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card 1 -->
    <div class="bg-white dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-2 h-full bg-danger"></div>
        <div class="flex justify-between items-start mb-4">
            <span class="px-2.5 py-1 bg-danger/10 text-danger text-[0.65rem] font-bold uppercase tracking-wider rounded-md">Cuaca Buruk</span>
            <span class="text-xs text-textMuted-light font-medium"><i class="fa-regular fa-clock"></i> 2 Hari Lalu</span>
        </div>
        <h3 class="font-bold text-lg text-textMain-light dark:text-textMain-dark mb-1">Gelombang Tinggi Menghambat Material</h3>
        <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-4">Pengiriman material pasir dan semen ke Pulau Barrang Lompo terhambat karena cuaca ekstrem dan peringatan BMKG.</p>
        
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3 mb-4 text-xs">
            <div class="flex items-center gap-2 text-textMuted-light mb-1"><i class="fa-solid fa-location-dot"></i> Lokasi: KNMP Kepulauan Seribu</div>
            <div class="flex items-center gap-2 text-textMuted-light"><i class="fa-solid fa-hard-hat"></i> Pelapor: Pengawas Lapangan A</div>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-4">
            <span class="text-xs font-bold text-danger"><i class="fa-solid fa-circle-xmark"></i> Belum Selesai</span>
            <button class="text-info text-sm font-bold hover:underline">Update Solusi</button>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-2 h-full bg-success"></div>
        <div class="flex justify-between items-start mb-4">
            <span class="px-2.5 py-1 bg-warning/10 text-warning text-[0.65rem] font-bold uppercase tracking-wider rounded-md">Sosial / Warga</span>
            <span class="text-xs text-textMuted-light font-medium"><i class="fa-regular fa-clock"></i> 1 Minggu Lalu</span>
        </div>
        <h3 class="font-bold text-lg text-textMain-light dark:text-textMain-dark mb-1">Penolakan Lokasi Pembuangan Limbah</h3>
        <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-4">Warga RT 02 memprotes penempatan bak penampungan sementara karena dinilai terlalu dekat dengan pemukiman.</p>
        
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3 mb-4 text-xs">
            <div class="flex items-center gap-2 text-textMuted-light mb-1"><i class="fa-solid fa-location-dot"></i> Lokasi: KNMP Sendang Biru</div>
            <div class="flex items-center gap-2 text-textMuted-light"><i class="fa-solid fa-hard-hat"></i> Pelapor: Kepala Desa</div>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-4">
            <span class="text-xs font-bold text-success"><i class="fa-solid fa-circle-check"></i> Sudah Selesai (Diselesaikan Musdes)</span>
            <button class="text-info text-sm font-bold hover:underline">Lihat BAST</button>
        </div>
    </div>
</div>
@endsection
