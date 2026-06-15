<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;

// Auth Routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Portal Routes
Route::get('/greetings', [PortalController::class, 'greetings'])->name('greetings');
Route::get('/users', [PortalController::class, 'users'])->name('users');

// API Internal Routes
Route::prefix('api/internal')->group(function () {
    Route::get('/regions/provinces', [\App\Http\Controllers\Api\RegionController::class, 'provinces']);
    Route::get('/regions/regencies/{provinceId}', [\App\Http\Controllers\Api\RegionController::class, 'regencies']);
    Route::get('/regions/districts/{regencyId}', [\App\Http\Controllers\Api\RegionController::class, 'districts']);
    Route::get('/regions/villages/{districtId}', [\App\Http\Controllers\Api\RegionController::class, 'villages']);
});

// KNMP Routes
Route::prefix('dashboard/knmp')->name('program.')->group(function () {
    Route::get('/siklus', [\App\Http\Controllers\Knmp\Dashboard\SiklusController::class, 'index'])->defaults('program', 'knmp')->name('dashboard.siklus');
    Route::get('/', [\App\Http\Controllers\Knmp\Dashboard\ProgresFisikController::class, 'index'])->defaults('program', 'knmp')->name('dashboard');
    Route::get('/export-pdf', [\App\Http\Controllers\Knmp\Dashboard\ExportController::class, 'pdf'])->defaults('program', 'knmp')->name('dashboard.export-pdf');
});
Route::prefix('master/knmp')->name('program.')->group(function () {
    Route::get('/batch', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'index'])->defaults('program', 'knmp')->name('master.batch');
    Route::post('/batch', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'store'])->defaults('program', 'knmp')->name('master.batch.store');
    Route::put('/batch/{id}', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'update'])->defaults('program', 'knmp')->name('master.batch.update');
    Route::delete('/batch/{id}', [\App\Http\Controllers\Knmp\Master\BatchController::class, 'destroy'])->defaults('program', 'knmp')->name('master.batch.destroy');

    // Kriteria Lokasi Routes
    Route::get('/kriteria-lokasi', [\App\Http\Controllers\Knmp\Master\KriteriaLokasiController::class, 'index'])->defaults('program', 'knmp')->name('master.kriteria-lokasi.index');
    Route::post('/kriteria-lokasi', [\App\Http\Controllers\Knmp\Master\KriteriaLokasiController::class, 'store'])->defaults('program', 'knmp')->name('master.kriteria-lokasi.store');
    Route::put('/kriteria-lokasi/{id}', [\App\Http\Controllers\Knmp\Master\KriteriaLokasiController::class, 'update'])->defaults('program', 'knmp')->name('master.kriteria-lokasi.update');
    Route::delete('/kriteria-lokasi/{id}', [\App\Http\Controllers\Knmp\Master\KriteriaLokasiController::class, 'destroy'])->defaults('program', 'knmp')->name('master.kriteria-lokasi.destroy');
    Route::get('/vendor', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'index'])->defaults('program', 'knmp')->name('master.vendor.index');
    Route::post('/vendor', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'store'])->defaults('program', 'knmp')->name('master.vendor.store');
    Route::put('/vendor/{id}', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'update'])->defaults('program', 'knmp')->name('master.vendor.update');
    Route::delete('/vendor/{id}', [\App\Http\Controllers\Knmp\Master\VendorController::class, 'destroy'])->defaults('program', 'knmp')->name('master.vendor.destroy');
    Route::get('/', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'index'])->defaults('program', 'knmp')->name('master');
    Route::get('/calon-lokasi', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'index'])->defaults('program', 'knmp')->name('master.calon-lokasi.index');
    Route::get('/calon-lokasi/create', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'create'])->defaults('program', 'knmp')->name('master.calon-lokasi.create');
    Route::post('/calon-lokasi/store', [\App\Http\Controllers\Knmp\Master\CalonLokasiController::class, 'store'])->defaults('program', 'knmp')->name('master.calon-lokasi.store');
});
Route::prefix('operasional/knmp')->name('program.')->group(function () {
    Route::post('/upload-foto', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'uploadFoto'])->defaults('program', 'knmp')->name('operasional.upload-foto');
    Route::post('/pindah-tahap', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'moveStage'])->defaults('program', 'knmp')->name('operasional.pindah-tahap');
    
    Route::get('/template-usulan', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'downloadTemplateUsulan'])->defaults('program', 'knmp')->name('operasional.template-usulan');
    Route::post('/import-usulan', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'importUsulan'])->defaults('program', 'knmp')->name('operasional.import-usulan');
    
    Route::get('/template-progres', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'downloadTemplateProgres'])->defaults('program', 'knmp')->name('operasional.template-progres');
    Route::post('/import-progres', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'importProgres'])->defaults('program', 'knmp')->name('operasional.import-progres');

    Route::get('/', [\App\Http\Controllers\Knmp\Operasional\PelaksanaanController::class, 'index'])->defaults('program', 'knmp')->name('operasional');
});
Route::prefix('evaluasi/knmp')->name('program.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Knmp\Evaluasi\KinerjaController::class, 'index'])->defaults('program', 'knmp')->name('evaluasi');
});

// BINS Routes
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

// Default Program Routes
Route::get('/dashboard/{program}', [\App\Http\Controllers\DefaultProgram\DashboardController::class, 'index'])->name('program.dashboard.default');
Route::get('/master/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\MasterController::class, 'index'])->name('program.master.default');
Route::get('/operasional/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\OperasionalController::class, 'index'])->name('program.operasional.default');
Route::get('/evaluasi/{program}/{menu?}', [\App\Http\Controllers\DefaultProgram\EvaluasiController::class, 'index'])->name('program.evaluasi.default');


