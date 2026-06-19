<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function greetings()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $programs = [
            [
                'name' => 'KNMP', 
                'icon' => 'fa-ship', 
                'color' => 'bg-info',
                'stats' => ['Lokasi Aktif' => '124', 'Progres Fisik' => '45.8%'],
                'narrative' => 'Pemantauan pembangunan Kampung Nelayan Merah Putih di seluruh pesisir Indonesia.'
            ],
            [
                'name' => 'Bioflok', 
                'icon' => 'fa-water', 
                'color' => 'bg-teal-light',
                'stats' => ['Kelompok' => '320', 'Volume' => '1,450 Ton'],
                'narrative' => 'Intensifikasi budidaya ikan sistem bioflok untuk mewujudkan ketahanan pangan.'
            ],
            [
                'name' => 'Minapadi', 
                'icon' => 'fa-seedling', 
                'color' => 'bg-success',
                'stats' => ['Lahan' => '4,500 Ha', 'Mitra' => '125 Desa'],
                'narrative' => 'Integrasi budidaya ikan dan padi untuk optimalisasi hasil pertanian dan perikanan.'
            ],
            [
                'name' => 'BINS', 
                'icon' => 'fa-box', 
                'color' => 'bg-warning',
                'stats' => ['Kolam' => '120', 'Panen' => '8,450 Ton'],
                'narrative' => 'Budidaya Ikan Nila Salin untuk substitusi impor dan peningkatan ekspor komoditas.'
            ],
            [
                'name' => 'Swasembada Garam', 
                'icon' => 'fa-cubes', 
                'color' => 'bg-navy-light',
                'stats' => ['Produksi' => '2.1M Ton', 'Kualitas' => 'K1 85%'],
                'narrative' => 'Akselerasi produksi garam nasional untuk memenuhi kebutuhan industri dan konsumsi.'
            ],
            [
                'name' => 'Revitalisasi Pantura', 
                'icon' => 'fa-anchor', 
                'color' => 'bg-blue-500',
                'stats' => ['Infrastruktur' => '45', 'Rehab' => '12 Titik'],
                'narrative' => 'Pemulihan ekosistem dan ekonomi pesisir pantai utara Jawa secara berkelanjutan.'
            ],
            [
                'name' => 'Modernisasi Kapal', 
                'icon' => 'fa-ferry', 
                'color' => 'bg-indigo-500',
                'stats' => ['Armada' => '85 Unit', 'Serapan' => '92%'],
                'narrative' => 'Pembaruan armada penangkapan ikan dengan teknologi navigasi dan alat tangkap modern.'
            ],
            [
                'name' => 'ISF Waingapu', 
                'icon' => 'fa-map-location-dot', 
                'color' => 'bg-purple-500',
                'stats' => ['Investasi' => 'Rp 2.4T', 'Progres' => '34%'],
                'narrative' => 'Pembangunan Integrated Shrimp Farming skala industri di Waingapu, Sumba Timur.'
            ],
            [
                'name' => 'Sarpras Pendidikan KP', 
                'icon' => 'fa-school', 
                'color' => 'bg-orange-500',
                'stats' => ['Poltek' => '12', 'Akademi' => '5'],
                'narrative' => 'Pengembangan sarana prasarana pendidikan untuk mencetak SDM unggul sektor KP.'
            ]
        ];
        
        return view('core.greetings', compact('programs'));
    }

    public function users()
    {
        if (!session('logged_in') || session('username') !== 'admin') {
            return redirect('/login');
        }
        return view('core.users', ['activeModule' => 'Pengguna', 'activeProgram' => 'Manajemen Sistem']);
    }
}
