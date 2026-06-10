@extends('layouts.app')

@section('title', 'Operasional - ' . $activeProgram)

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold mb-1">Operasional {{ $activeProgram }}</h2>
        <p class="text-textMuted-light dark:text-textMuted-dark text-sm">Input dan Pemantauan Data Lanjutan</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
    <!-- Form Lanjutan (Combined CRUD) -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 lg:col-span-1 h-fit">
        <h3 class="font-medium text-base border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">Form Pembaruan Operasional</h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Pilih Titik Lokasi</label>
                <select class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
                    <option>Kab. Demak - Lokasi Alpha</option>
                    <option>Kab. Gresik - Lokasi Beta</option>
                    <option>Kab. Sumbawa - Lokasi Gamma</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Progres (%)</label>
                    <input type="number" value="75" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light focus:ring-1 focus:ring-teal-light outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Status</label>
                    <select class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light outline-none transition-all">
                        <option>Berjalan</option>
                        <option>Selesai</option>
                        <option>Tertunda</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Catatan Lapangan</label>
                <textarea rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-light outline-none transition-all" placeholder="Tuliskan kendala atau informasi tambahan..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-textMuted-light dark:text-textMuted-dark uppercase tracking-wide mb-2">Unggah Dokumentasi</label>
                <div class="w-full border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-textMain-light mb-2"></i>
                    <p class="text-sm font-medium">Klik atau seret file kesini</p>
                    <p class="text-xs text-textMuted-light mt-1">Maks. 5MB (JPG, PNG, PDF)</p>
                </div>
            </div>

            <div class="pt-2">
                <button class="w-full py-3 rounded-md bg-gradient-to-r from-navy-light to-teal-light text-white font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5"> Simpan Laporan <i class="fa-solid fa-floppy-disk mr-2"></i> </button>
            </div>
        </div>
    </div>

    <!-- Data Master Detail View -->
    <div class="bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 lg:col-span-2">
        <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
            <h3 class="font-medium text-base">Histori Pembaruan Lokasi Terpilih</h3>
            <div class="flex gap-2">
                <button class="w-8 h-8 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50"><i class="fa-solid fa-filter text-xs"></i></button>
                <button class="w-8 h-8 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50"><i class="fa-solid fa-arrow-rotate-right text-xs"></i></button>
            </div>
        </div>

        <!-- Timeline Log / Interactive Data List -->
        <div class="space-y-4">
            @for($i=1; $i<=4; $i++)
            <div class="flex flex-col sm:flex-row gap-4 p-4 border border-gray-100 dark:border-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                <div class="w-16 h-16 rounded-xl bg-teal-light/10 flex flex-col items-center justify-center shrink-0 border border-teal-light/20">
                    <span class="text-xs font-medium text-textMain-light">MEI</span>
                    <span class="text-base font-black text-textMain-light">{{ 10 + $i }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-medium text-sm">Pembaruan Tahap {{ 5 - $i }} - Kab. Demak</h4>
                        <span class="px-2 py-0.5 bg-warning/10 text-warning text-[0.6rem] font-medium rounded uppercase">Revisi</span>
                    </div>
                    <p class="text-xs text-textMuted-light dark:text-textMuted-dark line-clamp-2">Pengerjaan fondasi kolam bioflok telah selesai namun terkendala cuaca hujan deras selama 2 hari terakhir. Progres fisik tetap dinaikkan 15% dari target awal.</p>
                    <div class="flex gap-4 mt-3">
                        <div class="text-[0.65rem] font-medium text-textMuted-light"><i class="fa-solid fa-user mr-1 text-textMain-light dark:text-blue-400"></i> Admin Daerah</div>
                        <div class="text-[0.65rem] font-medium text-textMuted-light"><i class="fa-solid fa-paperclip mr-1 text-textMain-light"></i> 2 Lampiran</div>
                    </div>
                </div>
                <div class="sm:border-l border-gray-100 dark:border-gray-700 sm:pl-4 flex flex-row sm:flex-col justify-end gap-2 shrink-0">
                    <button class="px-3 py-1.5 rounded bg-gray-100 dark:bg-gray-800 text-xs font-medium hover:bg-teal-light hover:text-white transition-colors">Detail</button>
                    <button class="px-3 py-1.5 rounded bg-gray-100 dark:bg-gray-800 text-xs font-medium text-danger hover:bg-danger hover:text-white transition-colors">Hapus</button>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
@endsection









