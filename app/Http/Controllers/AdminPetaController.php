<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class AdminPetaController extends Controller
{
    public function index(): View
    {
        try {
            $daftarBank = $this->daftarBank();
            $dataError = null;
        } catch (Throwable $exception) {
            report($exception);

            $daftarBank = collect();
            $dataError = 'Data peta gagal dimuat. Coba muat ulang halaman.';
        }

        $berkoordinat = $daftarBank->filter(
            fn (BankSampah $bank) => $bank->latitude !== null && $bank->longitude !== null,
        );

        return view('admin.peta', [
            'daftarBank' => $daftarBank,
            'berkoordinat' => $berkoordinat,
            'tanpaKoordinat' => $daftarBank->reject(
                fn (BankSampah $bank) => $bank->latitude !== null && $bank->longitude !== null,
            ),
            'dataError' => $dataError,
        ]);
    }

    /**
     * Daftar bank sampah untuk strip konteks, pin peta, dan daftar tanpa koordinat.
     * Diurutkan abjad supaya pin dan daftar mudah dirunut.
     *
     * @return Collection<int, BankSampah>
     */
    private function daftarBank(): Collection
    {
        return BankSampah::query()
            ->orderBy('nama_bank')
            ->get(['id', 'nama_bank', 'alamat', 'latitude', 'longitude']);
    }
}
