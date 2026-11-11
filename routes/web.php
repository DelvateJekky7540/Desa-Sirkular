<?php

use App\Http\Controllers\BankPenerimaanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bank/penerimaan', [BankPenerimaanController::class, 'index'])->name('bank.penerimaan');
Route::post('/bank/penerimaan', [BankPenerimaanController::class, 'store'])->name('bank.penerimaan.store');
