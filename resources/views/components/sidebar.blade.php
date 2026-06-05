@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

<aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 w-64 lg:w-0'" 
       class="fixed lg:relative z-40 h-full bg-bgSurface-light dark:bg-bgSurface-dark border-r border-gray-200 dark:border-gray-800 transition-all duration-300 shrink-0 overflow-hidden">
    
    <div class="w-64 h-full flex flex-col">
        <!-- Context Header -->
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 shrink-0 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-1">
                Modul {{ $activeModule }}
            </div>
            <div class="font-semibold text-teal-light dark:text-teal-dark flex items-center gap-2">
                <i class="fa-solid fa-layer-group"></i>
                {{ $activeProgram }}
            </div>
        </div>

        <!-- Navigation Items -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            
            @if(strtoupper($activeProgram) === 'KNMP')
                @if($activeModule === 'Dashboard')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Ringkasan Eksekutif</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                
                @elseif($activeModule === 'Master Data')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Tahapan Program</div>
                    <a href="/master/knmp?stage=usulan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage', 'usulan') === 'usulan' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-file-signature w-5 text-center {{ request()->query('stage', 'usulan') === 'usulan' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Usulan</span>
                    </a>
                    <a href="/master/knmp?stage=survey" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage') === 'survey' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-map-location-dot w-5 text-center {{ request()->query('stage') === 'survey' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Survey</span>
                    </a>
                    <a href="/master/knmp?stage=ded" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage') === 'ded' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-compass-drafting w-5 text-center {{ request()->query('stage') === 'ded' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">DED</span>
                    </a>
                    <a href="/master/knmp?stage=lelang" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage') === 'lelang' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-gavel w-5 text-center {{ request()->query('stage') === 'lelang' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Lelang</span>
                    </a>
                    <a href="/master/knmp?stage=konstruksi" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage') === 'konstruksi' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-helmet-safety w-5 text-center {{ request()->query('stage') === 'konstruksi' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Konstruksi</span>
                    </a>
                    <a href="/master/knmp?stage=serah-terima" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('stage') === 'serah-terima' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-handshake w-5 text-center {{ request()->query('stage') === 'serah-terima' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Serah Terima</span>
                    </a>

                @elseif($activeModule === 'Operasional')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Manajemen Data</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-calendar-check w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Harian</span>
                    </a>

                @elseif($activeModule === 'Evaluasi')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Pelaporan & Audit</div>
                    <a href="/evaluasi/knmp" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                @endif

            @elseif(strtoupper($activeProgram) === 'BIOFLOK')
                @if($activeModule === 'Dashboard')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Ringkasan Eksekutif</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                
                @elseif($activeModule === 'Master Data')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Referensi Program</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-water w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">KDMP</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-fish w-5 text-center"></i>
                        <span class="font-medium text-sm">SPPG</span>
                    </a>

                @elseif($activeModule === 'Operasional')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Manajemen Data</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>

                @elseif($activeModule === 'Evaluasi')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Pelaporan & Audit</div>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                @endif

            @elseif(strtoupper($activeProgram) === 'BINS')
                @if($activeModule === 'Dashboard')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Ringkasan Eksekutif</div>
                    <a href="/dashboard/bins" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-chart-line w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Dashboard Utama</span>
                    </a>
                
                @elseif($activeModule === 'Master Data')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Referensi Program</div>
                    <a href="/master/bins?type=petak" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('type', 'petak') === 'petak' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-draw-polygon w-5 text-center {{ request()->query('type', 'petak') === 'petak' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Petak</span>
                    </a>
                    <a href="/master/bins?type=kolam" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->query('type') === 'kolam' ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400' }}">
                        <i class="fa-solid fa-water w-5 text-center {{ request()->query('type') === 'kolam' ? 'text-white' : '' }}"></i>
                        <span class="font-medium text-sm">Kolam</span>
                    </a>

                @elseif($activeModule === 'Operasional')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Manajemen Data</div>
                    <a href="/operasional/bins" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>

                @elseif($activeModule === 'Evaluasi')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Pelaporan & Audit</div>
                    <a href="/evaluasi/bins" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                @endif

            @else
                <!-- Default Menus for Other Programs -->
                @if($activeModule === 'Dashboard')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Ringkasan Eksekutif</div>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Progres Fisik</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="font-medium text-sm">Produksi</span>
                    </a>
                    
                @elseif($activeModule === 'Operasional')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Manajemen Data</div>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-location-dot w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Data Lokasi / Titik</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                        <span class="font-medium text-sm">Input Progres Fisik</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-truck-ramp-box w-5 text-center"></i>
                        <span class="font-medium text-sm">Input Data Produksi</span>
                    </a>
                    
                @elseif($activeModule === 'Master Data')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Referensi Program</div>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-tags w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Kategori Indikator</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i>
                        <span class="font-medium text-sm">Penyedia / Vendor</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                        <i class="fa-solid fa-map-pin w-5 text-center"></i>
                        <span class="font-medium text-sm">Wilayah Tugas</span>
                    </a>
                    
                @elseif($activeModule === 'Evaluasi')
                    <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Pelaporan & Audit</div>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                        <i class="fa-solid fa-file-pdf w-5 text-center text-white"></i>
                        <span class="font-medium text-sm">Ekspor Laporan</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-teal-light">
                        <i class="fa-solid fa-check-double w-5 text-center"></i>
                        <span class="font-medium text-sm">Validasi Evaluasi</span>
                    </a>
                @endif
            @endif
            
        </div>
    </div>
</aside>
