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
                'icon' => 'fa-house-chimney', 
                'color' => 'bg-info',
                'stats' => ['Lokasi Aktif' => '124', 'Progres Fisik' => '45.8%'],
                'narrative' => 'Pemantauan pembangunan Kampung Nelayan Merah Putih di seluruh pesisir Indonesia.'
            ],
            [
                'name' => 'Budidaya Tematik', 
                'icon' => 'fa-fish', 
                'color' => 'bg-teal-light',
                'stats' => ['Kelompok' => '445', 'Lahan' => '5,950 Ha'],
                'narrative' => 'Pengembangan budidaya perikanan tematik berbasis kawasan dan kearifan lokal.'
            ],
            [
                'name' => 'BINS', 
                'icon' => 'fa-fish-fins', 
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
                'icon' => 'fa-water', 
                'color' => 'bg-blue-500',
                'stats' => ['Infrastruktur' => '45', 'Rehab' => '12 Titik'],
                'narrative' => 'Pemulihan ekosistem dan ekonomi pesisir pantai utara Jawa secara berkelanjutan.'
            ],
            [
                'name' => 'Modernisasi Kapal', 
                'icon' => 'fa-ship', 
                'color' => 'bg-indigo-500',
                'stats' => ['Armada' => '85 Unit', 'Serapan' => '92%'],
                'narrative' => 'Pembaruan armada penangkapan ikan dengan teknologi navigasi dan alat tangkap modern.'
            ],
            [
                'name' => 'ISF Waingapu', 
                'icon' => 'fa-shrimp', 
                'color' => 'bg-purple-500',
                'stats' => ['Investasi' => 'Rp 2.4T', 'Progres' => '34%'],
                'narrative' => 'Pembangunan Integrated Shrimp Farming skala industri di Waingapu, Sumba Timur.'
            ]
        ];
        
        if (Auth::user()->isUserDaerah()) {
            $programs = array_filter($programs, function ($prog) {
                return in_array(strtolower($prog['name']), ['knmp', 'budidaya tematik']);
            });
        }

        return view('core.greetings', compact('programs'));
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
            'kabupaten' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => \Illuminate\Support\Str::slug($request->name) . rand(1000, 9999) . '@system.local',
                'password' => Hash::make($request->password),
                'kabupaten' => $request->role === 'user_daerah' ? $request->kabupaten : null,
            ]);
            
            $user->assignRole($request->role);

            // Assign direct permissions to user
            if ($request->has('permissions') && is_array($request->permissions)) {
                $user->syncPermissions($request->permissions);
            }

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
        
        if ($user->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Data Super Admin tidak dapat diubah.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,'.$id,
            'role' => 'required|string',
            'kabupaten' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        try {
            $user->name = $request->name;
            $user->kabupaten = $request->role === 'user_daerah' ? $request->kabupaten : null;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $user->syncRoles([$request->role]);

            // Assign direct permissions to user
            if ($request->has('permissions') && is_array($request->permissions)) {
                $user->syncPermissions($request->permissions);
            } else {
                $user->syncPermissions([]);
            }

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

        if ($user->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Pengguna Super Admin tidak dapat dihapus.'], 403);
        }

        try {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Pengguna berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus pengguna: ' . $e->getMessage()], 500);
        }
    }
}
