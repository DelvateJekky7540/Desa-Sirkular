<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
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
        $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();
        $antreanError = null;

        try {
            $antrean = Setoran::query()
                ->with(['masyarakat.user', 'detailSampah.jenisSampah'])
                ->when($bankAktif !== null, fn ($query) => $query->where('bank_sampah_id', $bankAktif->id))
                ->where('status', Setoran::STATUS_MENUNGGU)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            $sudahDitimbangHariIni = Setoran::query()
                ->when($bankAktif !== null, fn ($query) => $query->where('bank_sampah_id', $bankAktif->id))
                ->where('status', Setoran::STATUS_DITIMBANG)
                ->whereDate('updated_at', now()->toDateString())
                ->count();
        } catch (Throwable $exception) {
            report($exception);

            $antrean = collect();
            $sudahDitimbangHariIni = 0;
            $antreanError = 'Antrean penimbangan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('bank.penimbangan', [
            'bankAktif' => $bankAktif,
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
                    $setoran->masyarakat?->user?->nama ?? 'warga',
                ));
        }

        $detail = $setoran->detailSampah()->orderBy('id')->first();

        if ($detail === null) {
            return redirect()
                ->route('bank.penimbangan')
                ->with('peringatan', sprintf(
                    'Setoran %s belum punya rincian jenis sampah, jadi belum bisa ditimbang.',
                    $setoran->masyarakat?->user?->nama ?? 'warga',
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

        DB::transaction(function () use ($setoran, $detail, $berat, $harga, $subtotal) {
            $detail->update([
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);

            $setoran->update([
                'total_berat' => $setoran->detailSampah()->sum('berat'),
                'total_nilai' => $setoran->detailSampah()->sum('subtotal'),
                'status' => Setoran::STATUS_DITIMBANG,
            ]);
        });

        $setoran->refresh()->load('masyarakat.user', 'detailSampah.jenisSampah');

        return redirect()
            ->route('bank.penimbangan')
            ->with('status', sprintf(
                'Setoran %s sudah ditimbang: %s %s senilai %s.',
                $setoran->masyarakat?->user?->nama ?? 'warga',
                Angka::kg($berat),
                $setoran->detailSampah->first()?->jenisSampah?->nama_sampah ?? 'sampah',
                Angka::rupiah($subtotal),
            ));
    }
}
