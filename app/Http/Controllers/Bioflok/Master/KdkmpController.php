<?php

namespace App\Http\Controllers\Bioflok\Master;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Bioflok\Kdkmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KdkmpController extends ProgramBaseController
{
    public function index(Request $request)
    {
        $this->checkAuth();
        $activeProgram = 'Budidaya Tematik';
        $activeModule = 'Master Data';

        $query = Kdkmp::orderBy('nama_kdkmp', 'asc');

        if (auth()->user()->isUserDaerah() && auth()->user()->kabupaten) {
            $query->where('kabupaten', auth()->user()->kabupaten);
        }

        $kdkmpList = $query->get();

        $provinsiList = Kdkmp::select('provinsi')->whereNotNull('provinsi')->where('provinsi', '!=', '')->distinct()->orderBy('provinsi')->pluck('provinsi');
        $kabupatenList = Kdkmp::select('kabupaten')->whereNotNull('kabupaten')->where('kabupaten', '!=', '')->distinct()->orderBy('kabupaten')->pluck('kabupaten');
        $komoditasList = Kdkmp::select('komoditas')->whereNotNull('komoditas')->where('komoditas', '!=', '')->distinct()->orderBy('komoditas')->pluck('komoditas');

        return view('programs.budidaya-tematik.master.kdkmp.index', compact(
            'activeProgram',
            'activeModule',
            'kdkmpList',
            'provinsiList',
            'kabupatenList',
            'komoditasList'
        ));
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'nama_kdkmp' => 'required|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'ketua_anggota' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'nama_penyuluh' => 'nullable|string|max:255',
            'no_hp_penyuluh' => 'nullable|string|max:255',
            'long' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
        ]);

        try {
            Kdkmp::create([
                'nama_kdkmp' => $request->nama_kdkmp,
                'provinsi' => $request->provinsi,
                'kabupaten' => $request->kabupaten,
                'desa' => $request->desa,
                'komoditas' => $request->komoditas,
                'ketua_anggota' => $request->ketua_anggota,
                'no_hp' => $request->no_hp,
                'nama_penyuluh' => $request->nama_penyuluh,
                'no_hp_penyuluh' => $request->no_hp_penyuluh,
                'long' => $request->long,
                'lat' => $request->lat,
            ]);

            return response()->json(['success' => true, 'message' => 'Data KDKMP berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan data KDKMP: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->checkAuth();

        $kdkmp = Kdkmp::findOrFail($id);

        $request->validate([
            'nama_kdkmp' => 'required|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'ketua_anggota' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'nama_penyuluh' => 'nullable|string|max:255',
            'no_hp_penyuluh' => 'nullable|string|max:255',
            'long' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
        ]);

        try {
            $kdkmp->update([
                'nama_kdkmp' => $request->nama_kdkmp,
                'provinsi' => $request->provinsi,
                'kabupaten' => $request->kabupaten,
                'desa' => $request->desa,
                'komoditas' => $request->komoditas,
                'ketua_anggota' => $request->ketua_anggota,
                'no_hp' => $request->no_hp,
                'nama_penyuluh' => $request->nama_penyuluh,
                'no_hp_penyuluh' => $request->no_hp_penyuluh,
                'long' => $request->long,
                'lat' => $request->lat,
            ]);

            return response()->json(['success' => true, 'message' => 'Data KDKMP berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui data KDKMP: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $this->checkAuth();

        $kdkmp = Kdkmp::findOrFail($id);

        try {
            $kdkmp->delete();
            return response()->json(['success' => true, 'message' => 'Data KDKMP berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data KDKMP: ' . $e->getMessage()], 500);
        }
    }
}
