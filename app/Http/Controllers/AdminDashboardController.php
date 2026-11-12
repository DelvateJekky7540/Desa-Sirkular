<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        try {
            $ringkasan = $this->ringkasan();
            $setoranTerbaru = $this->setoranTerbaru();
            $dataError = null;
        } catch (Throwable $exception) {
            report($exception);

            $ringkasan = ['berat' => 0.0, 'beratKonfirmasi' => 0.0, 'nilai' => 0.0];
            $setoranTerbaru = collect();
            $dataError = 'Data laporan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('admin.dashboard', [
            'ringkasan' => $ringkasan,
            'setoranTerbaru' => $setoranTerbaru,
            'dataError' => $dataError,
        ]);
    }

    /**
     * Strip angka kunci di atas laporan: total berat yang tercatat,
     * berat yang sudah dikonfirmasi masuk tabungan, dan nilai ekonomi setoran.
     *
     * @return array{berat: float, beratKonfirmasi: float, nilai: float}
     */
    private function ringkasan(): array
    {
        $baris = Setoran::query()
            ->whereIn('status', [Setoran::STATUS_DITIMBANG, Setoran::STATUS_DIKONFIRMASI])
            ->selectRaw('COALESCE(SUM(total_berat), 0) AS total_berat')
            ->selectRaw('COALESCE(SUM(CASE WHEN status = \''.Setoran::STATUS_DIKONFIRMASI.'\' THEN total_berat ELSE 0 END), 0) AS berat_konfirmasi')
            ->selectRaw('COALESCE(SUM(total_nilai), 0) AS total_nilai')
            ->first();

        return [
            'berat' => (float) $baris->total_berat,
            'beratKonfirmasi' => (float) $baris->berat_konfirmasi,
            'nilai' => (float) $baris->total_nilai,
        ];
    }

    /**
     * Setoran terbaru dari semua bank sampah untuk tabel monitoring.
     * Setoran dibatalkan tidak ikut: ia tidak pernah masuk rantai.
     *
     * @return Collection<int, Setoran>
     */
    private function setoranTerbaru(): Collection
    {
        return Setoran::query()
            ->with(['masyarakat.user', 'bankSampah'])
            ->where('status', '!=', Setoran::STATUS_DIBATALKAN)
            ->orderByDesc('tanggal_setoran')
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }
}
