<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TrackingController extends Controller
{
    /**
     * Form lacak setoran publik. Kode yang cocok dialihkan ke halaman hasil
     * supaya URL-nya bersih dan bisa dibagikan.
     */
    public function form(Request $request): View|RedirectResponse
    {
        $kode = strtoupper(trim((string) $request->query('kode', '')));

        if ($kode === '') {
            return view('tracking.form');
        }

        if (preg_match('/^ST-\d{8}-\d{3}$/', $kode) !== 1) {
            return view('tracking.form', ['kodeDiminta' => $kode])
                ->withErrors(['kode' => 'Format kode setoran belum tepat. Ketik ulang dengan bentuk ST-20261112-001, sesuai bukti setor dari bank sampah.']);
        }

        return redirect()->route('tracking.show', ['kode_setoran' => $kode]);
    }

    /**
     * Halaman perjalanan satu setoran, terbuka tanpa login. Kode yang tidak
     * dikenal tetap mendapat halaman 404 yang rapi, bukan halaman error generik.
     */
    public function show(string $kodeSetoran): View|Response
    {
        $kode = strtoupper(trim($kodeSetoran));

        $setoran = Setoran::query()
            ->with(['masyarakat.user', 'bankSampah', 'petugas', 'detailSampah.jenisSampah', 'mutasiSaldo'])
            ->where('kode_setoran', $kode)
            ->first();

        if ($setoran === null) {
            return response()->view('tracking.tidak-ditemukan', ['kode' => $kode], 404);
        }

        return view('tracking.show', ['setoran' => $setoran]);
    }
}
