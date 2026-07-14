# Template Dasar Manajemen Data (CRUD Boilerplate)

Folder `resources/views/templates/crud/` ini adalah standar boilerplate resmi untuk pembuatan halaman Master Data / CRUD pada sistem **Portal Program Prioritas Terintegrasi**.

Template ini dirancang untuk memanfaatkan semaksimal mungkin reusable components yang ada di `resources/views/components/` sehingga penulisan kode pada setiap modul baru menjadi sangat ringkas, bersih (*clean*), mudah dipelihara, serta 100% konsisten dengan standar UI terbaik (*Manajemen Pengguna*).

---

## 📂 Struktur File

```text
resources/views/templates/crud/
├── index.blade.php    # Halaman utama (Tabel, Search, Alpine.js State, Integrasi Modal)
├── create.blade.php   # Partial Modal Form untuk Input Data Baru (<x-modal-form>)
├── edit.blade.php     # Partial Modal Form untuk Edit Data Existing (<x-modal-form>)
└── README.md          # Dokumentasi penggunaan template (file ini)
```

---

## 🚀 Cara Mengambil & Menggunakan Template Ini

Untuk membuat modul CRUD baru (misal: **Master Wilayah** di bawah program `KNMP`), ikuti 3 langkah cepat berikut:

### 1. Salin Folder Template View
Copy folder `resources/views/templates/crud/` ke lokasi modul baru Anda:
```bash
cp -r resources/views/templates/crud resources/views/programs/knmp/master/wilayah
```

### 2. Buat Controller Baru
Buat Controller baru dengan mewarisi `ProgramBaseController`. Anda dapat mencontoh struktur `CrudTemplateController.php`:
```php
namespace App\Http\Controllers\Knmp\Master;

use App\Http\Controllers\ProgramBaseController;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WilayahController extends ProgramBaseController
{
    public function index(Request $request, $program)
    {
        $this->checkAuth();
        Gate::authorize('manage-master');
        
        return view('programs.knmp.master.wilayah.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $this->formatProgramName($program),
            'items' => Wilayah::orderBy('id', 'desc')->get()
        ]);
    }
    // ... method create, store, edit, update, destroy
}
```

### 3. Daftarkan Rute & Menu Sidebar
Di `routes/web.php`:
```php
Route::get('master/knmp/wilayah', [WilayahController::class, 'index'])->name('program.master.wilayah.index');
Route::post('master/knmp/wilayah', [WilayahController::class, 'store'])->name('program.master.wilayah.store');
Route::get('master/knmp/wilayah/create', [WilayahController::class, 'create'])->name('program.master.wilayah.create');
Route::get('master/knmp/wilayah/{id}/edit', [WilayahController::class, 'edit'])->name('program.master.wilayah.edit');
Route::put('master/knmp/wilayah/{id}', [WilayahController::class, 'update'])->name('program.master.wilayah.update');
Route::delete('master/knmp/wilayah/{id}', [WilayahController::class, 'destroy'])->name('program.master.wilayah.destroy');
```

Di `config/sidebar.php`:
```php
[
    'label' => 'Wilayah',
    'icon' => 'fa-map-pin',
    'url' => '/master/knmp/wilayah',
    'active' => ['master/knmp/wilayah*']
]
```

---

## 🧩 Komponen Reusable yang Digunakan

1. `<x-table.card>` (`resources/views/components/table/card.blade.php`)
   - Mengurus wrapper card tabel, judul, deskripsi, pencarian instan Alpine.js / server-side, serta dropdown jumlah data per halaman.
2. `<x-table.thead>`, `<x-table.tbody>`, `<x-table.tr>`, `<x-table.th>`, `<x-table.td>`
   - Komponen sel dan baris tabel dengan styling teks, *hover*, dan *dark mode* konsisten.
3. `<x-button-add>` (`resources/views/components/button-add.blade.php`)
   - Tombol tambah data standar dengan *border-radius* `rounded-md` dan warna `bg-teal-light`.
4. `<x-table.action-buttons>` (`resources/views/components/table/action-buttons.blade.php`)
   - Tombol aksi edit dan hapus (`w-8 h-8 rounded-md`) di setiap baris tabel.
5. `<x-modal-form>` (`resources/views/components/modal-form.blade.php`)
   - Komponen pembungkus modal form (untuk Create & Edit) dengan transisi halus, `backdrop-blur`, header standar, serta footer berisikan tombol *Batal* & *Simpan*.
6. `<x-confirm-modal>` (`resources/views/components/confirm-modal.blade.php`)
   - Modal konfirmasi hapus data beranimasi yang dipicu menggunakan `window.dispatchEvent(new CustomEvent('trigger-confirm', {...}))`.
