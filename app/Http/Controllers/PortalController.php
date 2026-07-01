<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PortalController extends Controller
{
    public function greetings()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
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
        
        if (Auth::user()->isUserDaerah()) {
            $programs = array_filter($programs, function ($prog) {
                return in_array(strtolower($prog['name']), ['knmp', 'bioflok', 'minapadi']);
            });
        }

        return view('core.greetings', compact('programs'));
    }

    public function users()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('manage-users')) {
            return redirect()->route('login');
        }
        
        $users = User::orderBy('created_at', 'desc')->get();
        
        $kabupatenList = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
            ->table('knmp')
            ->select('kabupaten')
            ->distinct()
            ->whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->orderBy('kabupaten', 'asc')
            ->pluck('kabupaten');

        return view('core.users', [
            'activeModule' => 'Pengguna', 
            'activeProgram' => 'Manajemen Sistem',
            'users' => $users,
            'kabupatenList' => $kabupatenList
        ]);
    }

    public function storeUser(Request $request)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('manage-users')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'kabupaten' => 'nullable|string'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => \Illuminate\Support\Str::slug($request->name) . rand(1000, 9999) . '@system.local',
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'kabupaten' => $request->role === 'User Daerah' ? $request->kabupaten : null,
            ]);

            return response()->json(['success' => true, 'message' => 'Pengguna berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan pengguna: ' . $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('manage-users')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,'.$id,
            'role' => 'required|string',
            'kabupaten' => 'nullable|string'
        ]);

        try {
            $user->name = $request->name;
            $user->role = $request->role;
            $user->kabupaten = $request->role === 'User Daerah' ? $request->kabupaten : null;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['success' => true, 'message' => 'Pengguna berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui pengguna: ' . $e->getMessage()], 500);
        }
    }

    public function destroyUser($id)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('manage-users')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun Anda sendiri.'], 400);
        }

        try {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Pengguna berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus pengguna: ' . $e->getMessage()], 500);
        }
    }
}
