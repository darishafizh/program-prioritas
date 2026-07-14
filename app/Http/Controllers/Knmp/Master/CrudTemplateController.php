<?php

namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CrudTemplateController extends ProgramBaseController
{
    /**
     * Menampilkan halaman Template Dasar CRUD (index)
     */
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        // Ambil data sample dari session atau inisialisasi default jika belum ada
        $items = session()->get('crud_template_items', [
            [
                'id' => 1,
                'kode' => 'REF-001',
                'nama' => 'Indikator Utama Produksi',
                'kategori' => 'Operasional',
                'status' => 'Aktif',
                'keterangan' => 'Parameter pengukuran standar'
            ],
            [
                'id' => 2,
                'kode' => 'REF-002',
                'nama' => 'Peralatan Monitoring Air',
                'kategori' => 'Infrastruktur',
                'status' => 'Aktif',
                'keterangan' => 'Alat ukur kualitas air otomatis'
            ],
            [
                'id' => 3,
                'kode' => 'REF-003',
                'nama' => 'Logistik Benih & Pakan',
                'kategori' => 'Logistik',
                'status' => 'Tidak Aktif',
                'keterangan' => 'Penyediaan cadangan benih berkala'
            ]
        ]);

        return view('templates.crud.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'items' => $items
        ]);
    }

    /**
     * Menampilkan form create dalam bentuk modal instan via direct route
     */
    public function create(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        $items = session()->get('crud_template_items', []);

        return view('templates.crud.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'items' => $items,
            'openCreateModal' => true
        ]);
    }

    /**
     * Menyimpan data baru (Store)
     */
    public function store(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'kode.required' => 'Kode wajib diisi.',
            'nama.required' => 'Nama data wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'status.required' => 'Status wajib dipilih.'
        ]);

        $items = session()->get('crud_template_items', []);
        
        // Buat ID baru
        $newId = count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;
        $validated['id'] = $newId;
        
        array_unshift($items, $validated);
        session()->put('crud_template_items', $items);

        return redirect()->route('program.master.template-crud.index', ['program' => $program])
                         ->with('success', 'Data baru berhasil ditambahkan pada template CRUD.');
    }

    /**
     * Menampilkan form edit dalam bentuk modal instan via direct route
     */
    public function edit(Request $request, $id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        $activeProgram = $this->formatProgramName($program);

        $items = session()->get('crud_template_items', []);
        $editItem = null;
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                $editItem = $item;
                break;
            }
        }

        if (!$editItem) {
            return redirect()->route('program.master.template-crud.index', ['program' => $program])
                             ->with('error', 'Data tidak ditemukan.');
        }

        return view('templates.crud.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'items' => $items,
            'openEditModal' => true,
            'editItem' => $editItem
        ]);
    }

    /**
     * Memperbarui data existing (Update)
     */
    public function update(Request $request, $id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'kode.required' => 'Kode wajib diisi.',
            'nama.required' => 'Nama data wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'status.required' => 'Status wajib dipilih.'
        ]);

        $items = session()->get('crud_template_items', []);
        $updated = false;

        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                $validated['id'] = (int) $id;
                $items[$key] = $validated;
                $updated = true;
                break;
            }
        }

        if ($updated) {
            session()->put('crud_template_items', $items);
            return redirect()->route('program.master.template-crud.index', ['program' => $program])
                             ->with('success', 'Data berhasil diperbarui.');
        }

        return redirect()->route('program.master.template-crud.index', ['program' => $program])
                         ->with('error', 'Data gagal diperbarui karena tidak ditemukan.');
    }

    /**
     * Menghapus data (Destroy)
     */
    public function destroy($id, $program = 'knmp')
    {
        $this->checkAuth();
        Gate::authorize('manage-master');

        $items = session()->get('crud_template_items', []);
        $newItems = [];
        $deleted = false;

        foreach ($items as $item) {
            if ($item['id'] != $id) {
                $newItems[] = $item;
            } else {
                $deleted = true;
            }
        }

        if ($deleted) {
            session()->put('crud_template_items', $newItems);
            return redirect()->back()->with('success', 'Data berhasil dihapus dari sistem.');
        }

        return redirect()->back()->with('error', 'Data gagal dihapus.');
    }
}
