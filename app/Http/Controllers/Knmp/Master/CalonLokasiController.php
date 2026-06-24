<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\CalonLokasi;
use App\Models\Knmp;
use App\Models\CalonLokasiPengajuan;
use App\Models\Region\Province;
use App\Models\Region\Regency;
use App\Models\Region\District;
use App\Models\Region\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class CalonLokasiController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $stage = $request->query('stage', 'pengajuan');
        
        $query = CalonLokasi::with(['user', 'knmp', 'detail', 'pengajuan', 'verifAdmin', 'baAktivasi', 'verifTeknis', 'baCalon', 'penetapan']);
        
        if (Auth::user()->isUserDaerah()) {
            $query->where('kabupaten', Auth::user()->kabupaten);
        }

        $allCalon = $query->get();
        
        $proposals = [];
        $verifList = [];
        $baAktivasiList = [];
        $verifTeknisList = [];
        $baCalonList = [];
        $penetapanList = [];

        foreach($allCalon as $calon) {
            $desa = $calon->desa ?? '-';
            $kecamatan = $calon->kecamatan ?? '-';
            $kabupaten = $calon->kabupaten ?? '-';
            $provinsi = $calon->provinsi ?? '-';
            $idUser = $calon->user ? $calon->user->name : ('#USR-'.$calon->user_id);

            // Penanganan Dokumen Pengajuan (URL Publik atau Local Storage)
            $dokumen = $calon->pengajuan?->dokumen_proposal;
            $dokumenLink = filter_var($dokumen, FILTER_VALIDATE_URL) ? $dokumen : ($dokumen ? asset('storage/' . $dokumen) : null);

            // Base Data untuk Detail Modal
            $baseData = [
                'id' => $calon->id,
                'idUser' => $idUser,
                'desa' => $desa,
                'kecamatan' => $kecamatan,
                'kabupaten' => $kabupaten,
                'provinsi' => $provinsi,
                'lat' => $calon->latitude,
                'lng' => $calon->longitude,
                'detail' => $calon->detail,
                'pengajuan' => $calon->pengajuan,
                'keterangan' => json_decode($calon->keterangan ?? '{}', true),
            ];

            if ($calon->status_tahapan == 'pengajuan') {
                $proposals[] = array_merge($baseData, [
                    'tanggal' => $calon->pengajuan?->tanggal_pengajuan ?? '-',
                    'dokumen' => $dokumenLink,
                    'kriteria' => '0/6'
                ]);
            } else if ($calon->status_tahapan == 'verif_admin') {
                $verifList[] = array_merge($baseData, [
                    'dokumen' => $dokumenLink,
                    'nilaiSkala' => ($calon->verifAdmin?->skor_nilai ?? 0) . '/100',
                    'status' => $calon->verifAdmin?->status_verif ?? 'Proses Review'
                ]);
            } else if ($calon->status_tahapan == 'ba_aktivasi') {
                $baAktivasiList[] = array_merge($baseData, [
                    'dokumen' => $calon->baAktivasi?->dokumen_ba ? asset('storage/' . $calon->baAktivasi->dokumen_ba) : null,
                    'status' => $calon->baAktivasi?->status_ba ?? 'Menunggu Draft'
                ]);
            } else if ($calon->status_tahapan == 'verif_teknis') {
                $verifTeknisList[] = array_merge($baseData, [
                    'dokumen' => $calon->baAktivasi?->dokumen_ba ? asset('storage/' . $calon->baAktivasi->dokumen_ba) : null,
                ]);
            } else if ($calon->status_tahapan == 'ba_calon') {
                $baCalonList[] = array_merge($baseData, [
                    'dokumen' => $calon->baCalon?->dokumen_ba ? asset('storage/' . $calon->baCalon->dokumen_ba) : null,
                    'nilaiSkala' => '-',
                    'status' => $calon->baCalon?->status_ba ?? 'Menunggu Draft'
                ]);
            } else if ($calon->status_tahapan == 'penetapan') {
                $penetapanList[] = array_merge($baseData, [
                    'dokumen' => $calon->penetapan?->dokumen_sk ? asset('storage/' . $calon->penetapan->dokumen_sk) : null,
                    'nilaiSkala' => '-',
                    'status' => $calon->penetapan?->status_sk ?? 'Menunggu Penerbitan'
                ]);
            }
        }

        $kriteriaLokasiList = \App\Models\KriteriaLokasi::all();

        return view('programs.knmp.master.calon-lokasi.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'stage' => $stage,
            'proposals' => $proposals,
            'verifList' => $verifList,
            'baAktivasiList' => $baAktivasiList,
            'verifTeknisList' => $verifTeknisList,
            'baCalonList' => $baCalonList,
            'penetapanList' => $penetapanList,
            'kriteriaLokasiList' => $kriteriaLokasiList
        ]);
    }

    public function create(Request $request, $program)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        $activeProgram = $this->formatProgramName($program);
        $kriteriaLokasiList = \App\Models\KriteriaLokasi::orderBy('id', 'asc')->get();

        return view('programs.knmp.master.calon-lokasi.create', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'kriteriaLokasiList' => $kriteriaLokasiList,
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');

        $request->validate([
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'link_dokumen' => 'required|url', // Tautan Dokumen Pendukung
        ]);

        try {
            DB::connection('mysql_knmp')->beginTransaction();

            // Dapatkan nama asli wilayah
            $provName = Province::find($request->provinsi)->name ?? '-';
            $kabName = Regency::find($request->kabupaten)->name ?? '-';
            $kecName = District::find($request->kecamatan)->name ?? '-';
            $desaName = Village::find($request->desa)->name ?? '-';

            // Simpan Semua File Tambahan secara dinamis (jika ada)
            $uploadedFiles = [];
            $proposalPath = $request->link_dokumen; // Ambil URL dari form input
            
            foreach ($request->allFiles() as $field => $fileData) {
                $files = is_array($fileData) ? $fileData : [$fileData];
                $paths = [];
                foreach ($files as $f) {
                    $filename = time() . '_' . $field . '_' . str_replace(' ', '_', $f->getClientOriginalName());
                    $paths[] = $f->storeAs('dokumen_tambahan', $filename, 'public');
                }
                $uploadedFiles[$field] = count($paths) > 1 ? $paths : $paths[0];
            }

            // Kumpulkan data JSON untuk kriteria tambahan (jika ada struktur dinamis lain)
            $jsonData = [
                'kriteria' => is_array($request->kriteria) ? $request->kriteria : json_decode($request->kriteria ?? '[]', true),
                'files' => $uploadedFiles
            ];

            // 1. (Dihapus) Tidak lagi menyimpan ke tabel knmp saat pengajuan awal

            // 2. Insert ke calon_lokasi
            $calonLokasi = CalonLokasi::create([
                'provinsi' => $provName,
                'kabupaten' => $kabName,
                'kecamatan' => $kecName,
                'desa' => $desaName,
                'latitude' => $request->q66_lampirkanTitik ?? null,
                'longitude' => $request->q67_masukkanTitik ?? null,
                'user_id' => Auth::id(),
                'status_tahapan' => 'pengajuan',
                'is_active' => true,
                'keterangan' => json_encode($jsonData),
            ]);

            // 3. Insert ke calon_lokasi_detail (Menampung jawaban kuesioner - non fisik)
            \App\Models\CalonLokasiDetail::create([
                'calon_lokasi_id' => $calonLokasi->id,
                'nama_pengisi' => $request->q14_5Nama,
                'jabatan_pengisi' => $request->q22_6Jabatan,
                'no_hp_pengisi' => $request->q15_7No,
                'status_kepemilikan' => $request->q24_typeA,
                'kesesuaian_rtrw' => $request->q30_typeA30,
                'is_mangrove' => $request->q32_typeA32,
                'is_konservasi' => $request->q33_3Apakah,
                'is_hutan_lindung' => $request->q34_4Apakah,
                'is_kawasan_budidaya' => $request->q35_5Apakah,
                'is_das' => $request->q40_9Apakah,
                'is_pasang_surut' => $request->q52_15Apakah,
            ]);

            // 4. Insert ke calon_lokasi_pengajuan (Tahap awal + Karakteristik Fisik)
            CalonLokasiPengajuan::create([
                'calon_lokasi_id' => $calonLokasi->id,
                'dokumen_proposal' => $proposalPath,
                'tanggal_pengajuan' => now(),
                'luas_lahan' => $request->q36_7Luas,
                'panjang_lahan' => $request->q68_panjangLahan,
                'lebar_lahan' => $request->q69_lebarLahan,
                'kemiringan_lahan' => $request->q43_10bJika43,
                'jarak_pantai' => $request->q38_7Luas38,
                'jarak_sungai' => $request->q41_jikaYa,
                'lebar_sungai' => $request->q42_jikaYa42,
                'tekstur_tanah' => $request->q44_typeA44,
                'salinitas_air' => $request->q50_typeA50,
            ]);

            DB::connection('mysql_knmp')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim.'
            ]);

        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $calon = CalonLokasi::findOrFail($id);
        $status = $request->input('status_tahapan');
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            $calon->status_tahapan = $status;
            $calon->save();

            // Jika pindah ke verif_admin, buat record awal untuk verifikasi jika belum ada
            if ($status == 'verif_admin') {
                \App\Models\CalonLokasiVerifAdmin::firstOrCreate([
                    'calon_lokasi_id' => $calon->id
                ], [
                    'status_verif' => 'Proses Review',
                    'skor_nilai' => 0
                ]);
            }

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeVerifAdmin(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'status_verif' => 'required|in:Lolos,Revisi,Ditolak'
        ]);

        $calon = CalonLokasi::findOrFail($id);
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            
            $verif = \App\Models\CalonLokasiVerifAdmin::firstOrCreate(
                ['calon_lokasi_id' => $calon->id],
                ['skor_nilai' => 0, 'status_verif' => 'Proses Review']
            );

            $verif->status_verif = $request->status_verif;
            $verif->catatan = $request->catatan;
            $verif->tanggal_verif = now();

            $verif->save();

            // Update main status based on verification result
            if ($request->status_verif == 'Lolos') {
                $calon->status_tahapan = 'ba_aktivasi';
                // Initialize BA Aktivasi record
                \App\Models\CalonLokasiBaAktivasi::firstOrCreate([
                    'calon_lokasi_id' => $calon->id
                ], [
                    'status_ba' => 'Menunggu Draft'
                ]);
            } else if ($request->status_verif == 'Revisi' || $request->status_verif == 'Ditolak') {
                $calon->status_tahapan = 'pengajuan';
            }
            
            $calon->save();

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data verifikasi berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeVerifTeknis(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'status_verif' => 'required|in:Lolos,Revisi,Ditolak'
        ]);

        $calon = CalonLokasi::findOrFail($id);
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            
            $verif = \App\Models\CalonLokasiVerifTeknis::firstOrCreate(
                ['calon_lokasi_id' => $calon->id],
                ['skor_teknis' => 0, 'status_verif' => 'Proses Survey']
            );

            $verif->status_verif = $request->status_verif;
            $verif->catatan = $request->catatan;
            $verif->tanggal_verif = now();

            $verif->save();

            // Update main status based on verification result
            if ($request->status_verif == 'Lolos') {
                $calon->status_tahapan = 'ba_calon';
                // Initialize BA Calon record
                \App\Models\CalonLokasiBaCalon::firstOrCreate([
                    'calon_lokasi_id' => $calon->id
                ], [
                    'status_ba' => 'Menunggu Draft'
                ]);
            } else if ($request->status_verif == 'Revisi' || $request->status_verif == 'Ditolak') {
                $calon->status_tahapan = 'pengajuan';
            }
            
            $calon->save();

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data verifikasi teknis berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadBaAktivasi(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'dokumen_ba' => 'required|file|mimes:pdf|max:2048'
        ]);

        $calon = CalonLokasi::findOrFail($id);
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            
            $ba = \App\Models\CalonLokasiBaAktivasi::firstOrCreate(
                ['calon_lokasi_id' => $calon->id],
                ['status_ba' => 'Menunggu Draft']
            );

            if ($request->hasFile('dokumen_ba')) {
                $file = $request->file('dokumen_ba');
                $filename = time() . '_ba_aktivasi_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('dokumen_ba', $filename, 'public');
                $ba->dokumen_ba = $path;
            }

            $ba->status_ba = 'Selesai';
            $ba->tanggal_ba = now();
            $ba->save();

            // Automatically move to verif_teknis
            $calon->status_tahapan = 'verif_teknis';
            
            // Initialize Verifikasi Teknis record
            \App\Models\CalonLokasiVerifTeknis::firstOrCreate([
                'calon_lokasi_id' => $calon->id
            ], [
                'status_verif' => 'Proses Survey'
            ]);

            $calon->save();

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Berita Acara Aktivasi berhasil diunggah.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadBaCalon(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'dokumen_ba' => 'required|file|mimes:pdf|max:2048'
        ]);

        $calon = CalonLokasi::findOrFail($id);
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            
            $ba = \App\Models\CalonLokasiBaCalon::firstOrCreate(
                ['calon_lokasi_id' => $calon->id],
                ['status_ba' => 'Menunggu Draft']
            );

            if ($request->hasFile('dokumen_ba')) {
                $file = $request->file('dokumen_ba');
                $filename = time() . '_ba_calon_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('dokumen_ba', $filename, 'public');
                $ba->dokumen_ba = $path;
            }

            $ba->status_ba = 'Selesai';
            $ba->tanggal_ba = now();
            $ba->save();

            // Automatically move to penetapan
            $calon->status_tahapan = 'penetapan';
            
            // Initialize Penetapan (SK) record
            \App\Models\CalonLokasiPenetapan::firstOrCreate([
                'calon_lokasi_id' => $calon->id
            ], [
                'status_sk' => 'Menunggu Penerbitan'
            ]);

            $calon->save();

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Berita Acara Calon berhasil diunggah.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadSkPenetapan(Request $request, $id)
    {
        $this->checkAuth();
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        
        $request->validate([
            'dokumen_sk' => 'required|file|mimes:pdf|max:10240'
        ]);

        $calon = CalonLokasi::findOrFail($id);
        
        try {
            DB::connection('mysql_knmp')->beginTransaction();
            
            $sk = \App\Models\CalonLokasiPenetapan::firstOrCreate(
                ['calon_lokasi_id' => $calon->id],
                ['status_sk' => 'Menunggu Penerbitan']
            );

            if ($request->hasFile('dokumen_sk')) {
                $file = $request->file('dokumen_sk');
                $filename = time() . '_sk_penetapan_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('dokumen_sk', $filename, 'public');
                $sk->dokumen_sk = $path;
            }

            $sk->status_sk = 'Ditetapkan';
            $sk->tanggal_sk = now();
            $sk->save();

            DB::connection('mysql_knmp')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'SK Penetapan berhasil diunggah.'
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql_knmp')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
