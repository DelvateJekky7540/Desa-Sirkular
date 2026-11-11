<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use Illuminate\View\View;
use Throwable;

class WargaEstimasiController extends Controller
{
    public function index(): View
    {
        $bankAktif = null;
        $daftarJenis = collect();
        $daftarError = null;

        try {
            $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();

            $daftarJenis = JenisSampah::query()
                ->aktif()
                ->orderBy('kategori')
                ->orderBy('nama_sampah')
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            $daftarError = 'Daftar harga acuan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('warga.estimasi', [
            'bankAktif' => $bankAktif,
            'daftarJenis' => $daftarJenis,
            'daftarError' => $daftarError,
        ]);
    }
}
