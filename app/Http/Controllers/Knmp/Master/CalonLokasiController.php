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
class CalonLokasiController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $stage = $request->query('stage', 'pengajuan');
        
        $allCalon = CalonLokasi::with(['knmp', 'pengajuan', 'verifAdmin', 'baAktivasi', 'verifTeknis', 'baCalon', 'penetapan'])->get();
        
        $proposals = [];
        $verifList = [];
        $baAktivasiList = [];
        $verifTeknisList = [];
        $baCalonList = [];
        $penetapanList = [];

        foreach($allCalon as $calon) {
            $desa = $calon->knmp->desa ?? '-';
            $kecamatan = $calon->knmp->kecamatan ?? '-';
            $kabupaten = $calon->knmp->kabupaten ?? '-';
            $idUser = '#USR-'.$calon->user_id;

            if ($calon->status_tahapan == 'pengajuan') {
                $proposals[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'kecamatan' => $kecamatan,
                    'kabupaten' => $kabupaten,
                    'tanggal' => $calon->pengajuan?->tanggal_pengajuan ?? '-',
                    'dokumen' => $calon->pengajuan?->dokumen_proposal ? asset('storage/' . $calon->pengajuan->dokumen_proposal) : null,
                    'kriteria' => '0/6'
                ];
            } else if ($calon->status_tahapan == 'verif_admin') {
                $verifList[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'kabupaten' => $kabupaten,
                    'dokumen' => $calon->pengajuan?->dokumen_proposal ? asset('storage/' . $calon->pengajuan->dokumen_proposal) : null,
                    'nilaiSkala' => ($calon->verifAdmin?->skor_nilai ?? 0) . '/100',
                    'status' => $calon->verifAdmin?->status_verif ?? 'Proses Review'
                ];
            } else if ($calon->status_tahapan == 'ba_aktivasi') {
                $baAktivasiList[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'kabupaten' => $kabupaten,
                    'dokumen' => $calon->baAktivasi?->dokumen_ba ? asset('storage/' . $calon->baAktivasi->dokumen_ba) : null,
                    'status' => $calon->baAktivasi?->status_ba ?? 'Menunggu Draft'
                ];
            } else if ($calon->status_tahapan == 'verif_teknis') {
                $verifTeknisList[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'dokumen' => $calon->verifTeknis?->dokumen_laporan ? asset('storage/' . $calon->verifTeknis->dokumen_laporan) : null,
                    'nilaiSkala' => ($calon->verifTeknis?->skor_teknis ?? 0) . '/100',
                    'status' => $calon->verifTeknis?->status_verif ?? 'Proses Survey'
                ];
            } else if ($calon->status_tahapan == 'ba_calon') {
                $baCalonList[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'dokumen' => $calon->baCalon?->dokumen_ba ? asset('storage/' . $calon->baCalon->dokumen_ba) : null,
                    'nilaiSkala' => '-',
                    'status' => $calon->baCalon?->status_ba ?? 'Menunggu Draft'
                ];
            } else if ($calon->status_tahapan == 'penetapan') {
                $penetapanList[] = [
                    'id' => $calon->id,
                    'idUser' => $idUser,
                    'desa' => $desa,
                    'kabupaten' => $kabupaten,
                    'dokumen' => $calon->penetapan?->dokumen_sk ? asset('storage/' . $calon->penetapan->dokumen_sk) : null,
                    'nilaiSkala' => '-',
                    'status' => $calon->penetapan?->status_sk ?? 'Menunggu Penerbitan'
                ];
            }
        }

        $kriteriaLokasiList = \App\Models\KriteriaLokasi::all();

        return view('programs.knmp.master.index', [
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
        $activeProgram = $this->formatProgramName($program);
        $kriteriaLokasiList = \App\Models\KriteriaLokasi::orderBy('id', 'asc')->get();

        return view('programs.knmp.master.create', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'kriteriaLokasiList' => $kriteriaLokasiList,
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'q20_proposal_knmp' => 'required|file|max:10240', // File Utama
        ]);

        try {
            DB::connection('mysql_knmp')->beginTransaction();

            // Dapatkan nama asli wilayah
            $provName = Province::find($request->provinsi)->name ?? '-';
            $kabName = Regency::find($request->kabupaten)->name ?? '-';
            $kecName = District::find($request->kecamatan)->name ?? '-';
            $desaName = Village::find($request->desa)->name ?? '-';

            // Simpan Semua File Tambahan secara dinamis
            $uploadedFiles = [];
            $proposalPath = null;
            
            foreach ($request->allFiles() as $field => $fileData) {
                if ($field === 'q20_proposal_knmp') {
                    $file = is_array($fileData) ? $fileData[0] : $fileData;
                    $filename = time() . '_proposal_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $proposalPath = $file->storeAs('dokumen_proposal', $filename, 'public');
                    continue;
                }
                
                $files = is_array($fileData) ? $fileData : [$fileData];
                $paths = [];
                foreach ($files as $f) {
                    $filename = time() . '_' . $field . '_' . str_replace(' ', '_', $f->getClientOriginalName());
                    $paths[] = $f->storeAs('dokumen_tambahan', $filename, 'public');
                }
                $uploadedFiles[$field] = count($paths) > 1 ? $paths : $paths[0];
            }

            // Kumpulkan data JSON untuk Keterangan Lahan secara dinamis
            // Abaikan field yang bukan isian pertanyaan lahan
            $excludedFields = ['_token', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kriteria', 'q20_proposal_knmp'];
            $lahanData = $request->except($excludedFields);

            $jsonData = [
                'form_data' => $lahanData,
                'kriteria' => is_array($request->kriteria) ? $request->kriteria : json_decode($request->kriteria ?? '[]', true),
                'files' => $uploadedFiles
            ];

            // 1. Insert ke tabel knmp
            $knmp = Knmp::create([
                'nama_lokasi' => $desaName . ', ' . $kecName . ', ' . $kabName . ', ' . $provName,
                'provinsi_id' => $request->provinsi,
                'kabupaten_id' => $request->kabupaten,
                'kecamatan_id' => $request->kecamatan,
                'desa_id' => $request->desa,
                'status' => 'active'
            ]);

            // 2. Insert ke calon_lokasi
            $calonLokasi = CalonLokasi::create([
                'knmp_id' => $knmp->id,
                'user_id' => Auth::id(),
                'status_tahapan' => 'pengajuan',
                'is_active' => true,
                'keterangan' => json_encode($jsonData),
            ]);

            // 3. Insert ke calon_lokasi_pengajuan
            CalonLokasiPengajuan::create([
                'calon_lokasi_id' => $calonLokasi->id,
                'dokumen_proposal' => $proposalPath,
                'tanggal_pengajuan' => now(),
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
}
