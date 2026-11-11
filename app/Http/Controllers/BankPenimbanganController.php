<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Support\Angka;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class BankPenimbanganController extends Controller
{
    public function index(): View
    {
        $antreanError = null;

        try {
            $antrean = Setoran::query()
                ->with(['user', 'detailSampah'])
                ->where('status', Setoran::STATUS_MENUNGGU)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            $sudahDitimbangHariIni = Setoran::query()
                ->where('status', Setoran::STATUS_SUDAH_DITIMBANG)
                ->whereDate('updated_at', now()->toDateString())
                ->count();
        } catch (Throwable $exception) {
            report($exception);

            $antrean = collect();
            $sudahDitimbangHariIni = 0;
            $antreanError = 'Antrean penimbangan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('bank.penimbangan', [
            'antrean' => $antrean,
            'sudahDitimbangHariIni' => $sudahDitimbangHariIni,
            'antreanError' => $antreanError,
            'ringkasan' => [
                'jumlah' => $antrean->count(),
                'berat' => (float) $antrean->sum('total_berat'),
                'nilai' => (float) $antrean->sum('total_nilai'),
            ],
        ]);
    }

    public function store(Request $request, Setoran $setoran): RedirectResponse
    {
        if ($setoran->status !== Setoran::STATUS_MENUNGGU) {
            return redirect()
                ->route('bank.penimbangan')
                ->with('peringatan', sprintf(
                    'Setoran %s sudah ditimbang sebelumnya. Antrean sudah diperbarui.',
                    $setoran->user?->name ?? 'warga',
                ));
        }

        $request->merge([
            'berat' => Angka::parseDesimal($request->input('berat')),
            'harga_per_kg' => Angka::parseDesimal($request->input('harga_per_kg'), true),
        ]);

        try {
            $data = $request->validate([
                'berat' => ['required', 'numeric', 'min:0.1', 'max:5000'],
                'harga_per_kg' => ['required', 'numeric', 'min:0', 'max:1000000'],
            ], [
                'berat.required' => 'Berat aktual belum diisi. Isi dulu sebelum menyimpan.',
                'berat.numeric' => 'Berat aktual harus berupa angka, contoh 12,5.',
                'berat.min' => 'Berat minimal 0,1 kg.',
                'berat.max' => 'Berat maksimal 5.000 kg per setoran.',
                'harga_per_kg.required' => 'Harga per kilogram belum diisi.',
                'harga_per_kg.numeric' => 'Harga harus berupa angka, contoh 4500.',
                'harga_per_kg.max' => 'Harga per kilogram di luar batas wajar.',
            ]);
        } catch (ValidationException $exception) {
            $request->session()->flash('penimbangan_id', $setoran->id);

            throw $exception;
        }

        $berat = (float) $data['berat'];
        $harga = (float) $data['harga_per_kg'];
        $subtotal = round($berat * $harga, 2);

        DB::transaction(function () use ($setoran, $berat, $harga, $subtotal) {
            $setoran->update([
                'total_berat' => $berat,
                'total_nilai' => $subtotal,
                'status' => Setoran::STATUS_SUDAH_DITIMBANG,
            ]);

            $setoran->detailSampah()->orderBy('id')->first()?->update([
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);
        });

        $setoran->refresh()->load('user', 'detailSampah');

        return redirect()
            ->route('bank.penimbangan')
            ->with('status', sprintf(
                'Setoran %s sudah ditimbang: %s %s senilai %s.',
                $setoran->user?->name ?? 'warga',
                Angka::kg($berat),
                $setoran->detailSampah->first()?->jenis_sampah ?? 'sampah',
                Angka::rupiah($subtotal),
            ));
    }
}
