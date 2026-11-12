<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\Setoran;
use Illuminate\View\View;
use Throwable;

class LandingController extends Controller
{
    public function index(): View
    {
        try {
            $statistik = $this->statistik();
            $statistikError = null;
        } catch (Throwable $exception) {
            report($exception);

            $statistik = ['berat' => 0.0, 'nilai' => 0.0, 'bank' => 0];
            $statistikError = 'Angka ringkasan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('welcome', [
            'statistik' => $statistik,
            'statistikError' => $statistikError,
        ]);
    }

    /**
     * Ringkasan rantai sampah untuk strip hero landing:
     * berat dan nilai setoran yang sudah ditimbang atau dikonfirmasi,
     * plus jumlah bank sampah aktif.
     *
     * @return array{berat: float, nilai: float, bank: int}
     */
    private function statistik(): array
    {
        $baris = Setoran::query()
            ->whereIn('status', [Setoran::STATUS_DITIMBANG, Setoran::STATUS_DIKONFIRMASI])
            ->selectRaw('COALESCE(SUM(total_berat), 0) AS total_berat')
            ->selectRaw('COALESCE(SUM(total_nilai), 0) AS total_nilai')
            ->first();

        return [
            'berat' => (float) $baris->total_berat,
            'nilai' => (float) $baris->total_nilai,
            'bank' => (int) BankSampah::query()->aktif()->count(),
        ];
    }
}
