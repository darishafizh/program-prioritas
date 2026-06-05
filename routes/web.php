<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProgramController;

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

// Program Specific Routes
Route::get('/dashboard/{program}', [ProgramController::class, 'dashboard'])->name('program.dashboard');
Route::get('/master/{program}', [ProgramController::class, 'master'])->name('program.master');
Route::get('/operasional/{program}', [ProgramController::class, 'operasional'])->name('program.operasional');
Route::get('/evaluasi/{program}', [ProgramController::class, 'evaluasi'])->name('program.evaluasi');

