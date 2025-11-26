<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GajiController;

// Tampilkan form login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard / index
Route::get('/', function () {
    return view('home'); // nanti Blade dashboard
})->middleware('auth');

// Karyawan
Route::prefix('data-karyawan')->middleware('auth')->group(function() {
    Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::post('/update', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::post('/destroy', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
});

// Gaji Karyawan
Route::prefix('gaji-karyawan')->middleware('auth')->group(function(){
    Route::get('/', [GajiController::class,'index'])->name('gaji.index');
    Route::post('/store', [GajiController::class,'store'])->name('gaji.store');
    Route::post('/update', [GajiController::class,'update'])->name('gaji.update');
    Route::post('/destroy', [GajiController::class,'destroy'])->name('gaji.destroy');
});