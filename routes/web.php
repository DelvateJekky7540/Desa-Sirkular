<?php

use App\Http\Controllers\BankPenerimaanController;
use App\Http\Controllers\BankPenimbanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bank/penerimaan', [BankPenerimaanController::class, 'index'])->name('bank.penerimaan');
Route::post('/bank/penerimaan', [BankPenerimaanController::class, 'store'])->name('bank.penerimaan.store');

Route::get('/bank/penimbangan', [BankPenimbanganController::class, 'index'])->name('bank.penimbangan');
Route::post('/bank/penimbangan/{setoran}', [BankPenimbanganController::class, 'store'])->name('bank.penimbangan.store');
