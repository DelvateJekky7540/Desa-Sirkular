<?php

use App\Http\Controllers\BankPenerimaanController;
use App\Http\Controllers\BankPenimbanganController;
use App\Http\Controllers\WargaEstimasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bank/penerimaan', [BankPenerimaanController::class, 'index'])->name('bank.penerimaan');
Route::post('/bank/penerimaan', [BankPenerimaanController::class, 'store'])->name('bank.penerimaan.store');

Route::get('/bank/penimbangan', [BankPenimbanganController::class, 'index'])->name('bank.penimbangan');
Route::post('/bank/penimbangan/{setoran}', [BankPenimbanganController::class, 'store'])->name('bank.penimbangan.store');

Route::redirect('/warga', '/warga/beranda');

Route::get('/warga/estimasi', [WargaEstimasiController::class, 'index'])->name('warga.estimasi');

Route::view('/warga/beranda', 'warga.beranda')->name('warga.beranda');
Route::view('/warga/setor', 'warga.setor')->name('warga.setor');
Route::view('/warga/riwayat', 'warga.riwayat')->name('warga.riwayat');
Route::view('/warga/edukasi', 'warga.edukasi')->name('warga.edukasi');
