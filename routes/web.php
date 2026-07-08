<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;

// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Portal Routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/greetings', [PortalController::class, 'greetings'])->name('greetings');

    // User Management - Super Admin only
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users', [PortalController::class, 'users'])->name('users');
        Route::post('/users', [PortalController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [PortalController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [PortalController::class, 'destroyUser'])->name('users.destroy');
    });

    // API Internal Routes
    Route::prefix('api/internal')->group(function () {
        Route::get('/regions/provinces', [\App\Http\Controllers\Api\RegionController::class, 'provinces']);
        Route::get('/regions/regencies/{provinceId}', [\App\Http\Controllers\Api\RegionController::class, 'regencies']);
        Route::get('/regions/districts/{regencyId}', [\App\Http\Controllers\Api\RegionController::class, 'districts']);
        Route::get('/regions/villages/{districtId}', [\App\Http\Controllers\Api\RegionController::class, 'villages']);
    });

    // ==========================================
    // KNMP Routes
    // ==========================================

    // Dashboard - Super Admin, Admin Roren, Verifikator
    Route::prefix('dashboard/knmp')->name('program.')->middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::get('/siklus', [\App\Http\Controllers\Knmp\Dashboard\SiklusController::class, 'index'])->defaults('program', 'knmp')->name('dashboard.siklus');
        Route::get('/', [\App\Http\Controllers\Knmp\Dashboard\ProgresFisikController::class, 'index'])->defaults('program', 'knmp')->name('dashboard');
        Route::get('/export-pdf', [\App\Http\Controllers\Knmp\Dashboard\ExportController::class, 'pdf'])->defaults('program', 'knmp')->name('dashboard.export-pdf');
    });

    // Master - KNMP
    Route::prefix('master/knmp')->name('program.')->group(function () {
        // Batch & Vendor - Super Admin only
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/batch', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'index'])->defaults('program', 'knmp')->name('master.batch');
            Route::post('/batch', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'store'])->defaults('program', 'knmp')->name('master.batch.store');
            Route::put('/batch/{id}', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'update'])->defaults('program', 'knmp')->name('master.batch.update');
            Route::delete('/batch/{id}', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'destroy'])->defaults('program', 'knmp')->name('master.batch.destroy');

            Route::get('/vendor', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'index'])->defaults('program', 'knmp')->name('master.vendor.index');
            Route::post('/vendor', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'store'])->defaults('program', 'knmp')->name('master.vendor.store');
            Route::put('/vendor/{id}', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'update'])->defaults('program', 'knmp')->name('master.vendor.update');
            Route::delete('/vendor/{id}', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'destroy'])->defaults('program', 'knmp')->name('master.vendor.destroy');
        });

        // Calon Lokasi - All authenticated users (with per-action authorization in controller)
        Route::get('/', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'index'])->defaults('program', 'knmp')->name('master');
        Route::get('/calon-lokasi', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'index'])->defaults('program', 'knmp')->name('master.calon-lokasi.index');
        Route::get('/calon-lokasi/create', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'create'])->defaults('program', 'knmp')->name('master.calon-lokasi.create');
        Route::post('/calon-lokasi/store', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'store'])->defaults('program', 'knmp')->name('master.calon-lokasi.store');
        Route::post('/calon-lokasi/{id}/update-status', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'updateStatus'])->defaults('program', 'knmp')->name('master.calon-lokasi.update-status');
        Route::post('/calon-lokasi/{id}/verif-admin', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'storeVerifAdmin'])->defaults('program', 'knmp')->name('master.calon-lokasi.store-verif-admin');
        Route::post('/calon-lokasi/{id}/ba-aktivasi', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'uploadBaAktivasi'])->defaults('program', 'knmp')->name('master.calon-lokasi.upload-ba-aktivasi');
        Route::post('/calon-lokasi/{id}/verif-teknis', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'storeVerifTeknis'])->defaults('program', 'knmp')->name('master.calon-lokasi.store-verif-teknis');
        Route::post('/calon-lokasi/{id}/ba-calon', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'uploadBaCalon'])->defaults('program', 'knmp')->name('master.calon-lokasi.upload-ba-calon');
        Route::post('/calon-lokasi/{id}/penetapan', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'uploadSkPenetapan'])->defaults('program', 'knmp')->name('master.calon-lokasi.upload-sk-penetapan');
    });

    // Operasional - Super Admin, Admin Roren, Verifikator
    Route::prefix('operasional/knmp')->name('program.')->middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::post('/upload-foto', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'uploadFoto'])->defaults('program', 'knmp')->name('operasional.upload-foto');
        Route::post('/pindah-tahap', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'moveStage'])->defaults('program', 'knmp')->name('operasional.pindah-tahap');
        
        Route::get('/template-usulan', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'downloadTemplateUsulan'])->defaults('program', 'knmp')->name('operasional.template-usulan');
        Route::post('/import-usulan', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'importUsulan'])->defaults('program', 'knmp')->name('operasional.import-usulan');
        
        Route::get('/template-progres', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'downloadTemplateProgres'])->defaults('program', 'knmp')->name('operasional.template-progres');
        Route::post('/import-progres', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'importProgres'])->defaults('program', 'knmp')->name('operasional.import-progres');

        Route::get('/', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'index'])->defaults('program', 'knmp')->name('operasional');
    });

    // Evaluasi - Super Admin, Admin Roren, Verifikator
    Route::prefix('evaluasi/knmp')->name('program.')->middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::get('/', function () { return redirect()->route('program.evaluasi.calon-lokasi'); });
        Route::get('/calon-lokasi', [\App\Http\Controllers\Knmp\Evaluasi\CalonLokasiEvaluasiController::class, 'index'])->defaults('program', 'knmp')->name('evaluasi.calon-lokasi');
        Route::get('/calon-lokasi/pdf', [\App\Http\Controllers\Knmp\Evaluasi\CalonLokasiEvaluasiController::class, 'pdf'])->defaults('program', 'knmp')->name('evaluasi.calon-lokasi.pdf');
        Route::get('/operasional', [\App\Http\Controllers\Knmp\Evaluasi\OperasionalEvaluasiController::class, 'index'])->defaults('program', 'knmp')->name('evaluasi.operasional');
        Route::get('/operasional/pdf', [\App\Http\Controllers\Knmp\Evaluasi\OperasionalEvaluasiController::class, 'pdf'])->defaults('program', 'knmp')->name('evaluasi.operasional.pdf');
        Route::get('/progres-fisik', [\App\Http\Controllers\Knmp\Evaluasi\ProgresFisikEvaluasiController::class, 'index'])->defaults('program', 'knmp')->name('evaluasi.progres-fisik');
        Route::get('/progres-fisik/pdf', [\App\Http\Controllers\Knmp\Evaluasi\ProgresFisikEvaluasiController::class, 'pdf'])->defaults('program', 'knmp')->name('evaluasi.progres-fisik.pdf');
    });

    // ==========================================
    // BINS Routes - Super Admin, Admin Roren, Verifikator
    // ==========================================
    Route::middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::prefix('dashboard/bins')->name('program.bins.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bins\DashboardController::class, 'index'])->defaults('program', 'bins')->name('dashboard');
        });
        Route::prefix('master/bins')->name('program.bins.')->group(function () {
            Route::get('/{menu?}', [\App\Http\Controllers\Bins\MasterController::class, 'index'])->defaults('program', 'bins')->name('master');
        });
        Route::prefix('operasional/bins')->name('program.bins.')->group(function () {
            Route::get('/{menu?}', [\App\Http\Controllers\Bins\OperasionalController::class, 'index'])->defaults('program', 'bins')->name('operasional');
        });
        Route::prefix('evaluasi/bins')->name('program.bins.')->group(function () {
            Route::get('/{menu?}', [\App\Http\Controllers\Bins\EvaluasiController::class, 'index'])->defaults('program', 'bins')->name('evaluasi');
        });
    });

    // ==========================================
    // Bioflok Routes - Super Admin, Admin Roren, Verifikator
    // ==========================================
    Route::middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::prefix('dashboard/bioflok')->name('program.bioflok.dashboard.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bioflok\DashboardController::class, 'produksi'])->defaults('program', 'bioflok')->name('index');
            Route::get('/progres-fisik', [\App\Http\Controllers\Bioflok\DashboardController::class, 'progresFisik'])->defaults('program', 'bioflok')->name('progres-fisik');
            Route::get('/produksi', [\App\Http\Controllers\Bioflok\DashboardController::class, 'produksi'])->defaults('program', 'bioflok')->name('produksi');
            Route::get('/export-pdf', [\App\Http\Controllers\Bioflok\DashboardController::class, 'exportProduksiPdf'])->defaults('program', 'bioflok')->name('export-pdf');
            Route::get('/produksi/export-pdf', [\App\Http\Controllers\Bioflok\DashboardController::class, 'exportProduksiPdf'])->defaults('program', 'bioflok')->name('produksi.export-pdf');
        });
        Route::prefix('evaluasi/bioflok')->name('program.bioflok.evaluasi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bioflok\EvaluasiController::class, 'produksi'])->defaults('program', 'bioflok')->name('index');
            Route::get('/progres-fisik', [\App\Http\Controllers\Bioflok\EvaluasiController::class, 'progresFisik'])->defaults('program', 'bioflok')->name('progres-fisik');
            Route::get('/produksi', [\App\Http\Controllers\Bioflok\EvaluasiController::class, 'produksi'])->defaults('program', 'bioflok')->name('produksi');
        });
        Route::prefix('operasional/bioflok')->name('program.bioflok.operasional.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bioflok\DashboardController::class, 'produksi'])->defaults('program', 'bioflok')->name('index');
            Route::get('/progres-fisik', [\App\Http\Controllers\Bioflok\DashboardController::class, 'progresFisik'])->defaults('program', 'bioflok')->name('progres-fisik');
            Route::get('/produksi', [\App\Http\Controllers\Bioflok\DashboardController::class, 'produksi'])->defaults('program', 'bioflok')->name('produksi');
        });
    });

    // ==========================================
    // Bioflok Master Routes
    // ==========================================
    Route::middleware('role:super_admin,admin_roren,verifikator,user_daerah')->group(function () {
        Route::prefix('master/bioflok')->name('program.bioflok.master.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bioflok\Master\KdkmpController::class, 'index'])->defaults('program', 'bioflok')->name('index');
            Route::get('/kdkmp', [\App\Http\Controllers\Bioflok\Master\KdkmpController::class, 'index'])->defaults('program', 'bioflok')->name('kdkmp.index');
            Route::post('/kdkmp', [\App\Http\Controllers\Bioflok\Master\KdkmpController::class, 'store'])->defaults('program', 'bioflok')->name('kdkmp.store');
            Route::put('/kdkmp/{id}', [\App\Http\Controllers\Bioflok\Master\KdkmpController::class, 'update'])->defaults('program', 'bioflok')->name('kdkmp.update');
            Route::delete('/kdkmp/{id}', [\App\Http\Controllers\Bioflok\Master\KdkmpController::class, 'destroy'])->defaults('program', 'bioflok')->name('kdkmp.destroy');
        });
    });

    // ==========================================
    // Redirect Budidaya Tematik -> Bioflok
    // ==========================================
    Route::redirect('/dashboard/budidaya-tematik', '/dashboard/bioflok');
    Route::redirect('/master/budidaya-tematik', '/master/bioflok/kdkmp');
    Route::redirect('/operasional/budidaya-tematik', '/operasional/bioflok');
    Route::redirect('/evaluasi/budidaya-tematik', '/evaluasi/bioflok');

    // ==========================================
    // Default Program Routes
    // ==========================================
    Route::middleware('role:super_admin,admin_roren,verifikator')->group(function () {
        Route::get('/dashboard/{program}', [\App\Http\Controllers\DefaultProgram\DashboardController::class, 'index'])->name('program.dashboard.default');
        Route::get('/operasional/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\OperasionalController::class, 'index'])->name('program.operasional.default');
        Route::get('/evaluasi/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\EvaluasiController::class, 'index'])->name('program.evaluasi.default');
    });

    Route::middleware('role:super_admin,admin_roren,verifikator,user_daerah')->group(function () {
        Route::get('/master/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\MasterController::class, 'index'])->name('program.master.default');
    });
});
