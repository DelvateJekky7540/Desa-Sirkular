<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPetaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankPenerimaanController;
use App\Http\Controllers\BankPenimbanganController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MitraKatalogController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WargaEstimasiController;
use App\Http\Controllers\WargaSetoranController;
use App\Models\BankSampah;
use App\Models\JenisSampah;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/tracking', [TrackingController::class, 'form'])->name('tracking.form');
Route::get('/tracking/{kode_setoran}', [TrackingController::class, 'show'])
    ->where('kode_setoran', '[A-Za-z0-9\-]+')
    ->name('tracking.show');

Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:5,1');
Route::post('/login/demo/{peran}', [AuthController::class, 'demo'])
    ->where('peran', 'admin|bank|warga|mitra')
    ->name('login.demo')
    ->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

Route::get('/admin/peta', [AdminPetaController::class, 'index'])
    ->name('admin.peta')
    ->middleware('auth');

Route::get('/bank/penerimaan', [BankPenerimaanController::class, 'index'])->name('bank.penerimaan');
Route::post('/bank/penerimaan', [BankPenerimaanController::class, 'store'])->name('bank.penerimaan.store');

Route::get('/bank/penimbangan', [BankPenimbanganController::class, 'index'])->name('bank.penimbangan');
Route::post('/bank/penimbangan/{setoran}', [BankPenimbanganController::class, 'store'])->name('bank.penimbangan.store');

Route::redirect('/warga', '/warga/beranda');

Route::get('/warga/estimasi', [WargaEstimasiController::class, 'index'])->name('warga.estimasi');

Route::view('/warga/beranda', 'warga.beranda')->name('warga.beranda');
Route::get('/warga/setor', [WargaSetoranController::class, 'index'])->name('warga.setor');
Route::post('/warga/setor', [WargaSetoranController::class, 'store'])->name('warga.setor.store');
Route::view('/warga/riwayat', 'warga.riwayat')->name('warga.riwayat');
Route::view('/warga/edukasi', 'warga.edukasi')->name('warga.edukasi');

Route::get('/mitra/katalog', [MitraKatalogController::class, 'index'])->name('mitra.katalog');

Route::get('/mitra/penawaran', function () {
    return view('mitra.penawaran', [
        'jenisTerpilih' => JenisSampah::query()->find((int) request('jenis')),
        'bankTerpilih' => BankSampah::query()->find((int) request('bank')),
    ]);
})->name('mitra.penawaran');
