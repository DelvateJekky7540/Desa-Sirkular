<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Models\User;
use App\Support\Angka;
use App\Support\JenisSampah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class BankPenerimaanController extends Controller
{
    public function index(): View
    {
        $riwayatError = null;

        try {
            $riwayat = Setoran::query()
                ->with(['user', 'detailSampah'])
                ->whereDate('tanggal', now()->toDateString())
                ->latest('id')
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            $riwayat = collect();
            $riwayatError = 'Riwayat setoran hari ini gagal dimuat. Coba muat ulang halaman.';
        }

        return view('bank.penerimaan', [
            'daftarWarga' => User::query()->orderBy('name')->get(['id', 'name']),
            'daftarJenis' => JenisSampah::semua(),
            'riwayat' => $riwayat->take(5),
            'jumlahRiwayat' => $riwayat->count(),
            'riwayatError' => $riwayatError,
            'ringkasan' => [
                'jumlah' => $riwayat->count(),
                'berat' => (float) $riwayat->sum('total_berat'),
                'nilai' => (float) $riwayat->sum('total_nilai'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'berat' => $this->angkaDesimal($request->input('berat')),
            'harga_per_kg' => $this->angkaDesimal($request->input('harga_per_kg'), true),
        ]);

        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'jenis_sampah' => ['required', 'string', Rule::in(JenisSampah::nama())],
            'berat' => ['required', 'numeric', 'min:0.1', 'max:5000'],
            'harga_per_kg' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'user_id.required' => 'Pilih warga yang menyetor sampah.',
            'user_id.exists' => 'Warga yang dipilih tidak ditemukan.',
            'jenis_sampah.required' => 'Pilih jenis sampah yang ditimbang.',
            'jenis_sampah.in' => 'Jenis sampah belum terdaftar di harga acuan.',
            'berat.required' => 'Berat sampah belum diisi. Isi dulu sebelum menyimpan.',
            'berat.numeric' => 'Berat harus berupa angka, contoh 12,5.',
            'berat.min' => 'Berat minimal 0,1 kg.',
            'berat.max' => 'Berat maksimal 5.000 kg per setoran.',
            'harga_per_kg.required' => 'Harga per kilogram belum diisi.',
            'harga_per_kg.numeric' => 'Harga harus berupa angka, contoh 4500.',
            'harga_per_kg.max' => 'Harga per kilogram di luar batas wajar.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ]);

        $berat = (float) $data['berat'];
        $harga = (float) $data['harga_per_kg'];
        $subtotal = round($berat * $harga, 2);

        $setoran = DB::transaction(function () use ($data, $berat, $harga, $subtotal) {
            $setoran = Setoran::query()->create([
                'user_id' => $data['user_id'],
                'tanggal' => now()->toDateString(),
                'total_berat' => $berat,
                'total_nilai' => $subtotal,
                'status' => Setoran::STATUS_MENUNGGU,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $setoran->detailSampah()->create([
                'jenis_sampah' => $data['jenis_sampah'],
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);

            return $setoran;
        });

        $setoran->load('user');

        return redirect()
            ->route('bank.penerimaan')
            ->with('status', sprintf(
                'Setoran %s tercatat: %s %s senilai %s. Status: %s.',
                $setoran->user->name,
                Angka::kg($berat),
                $data['jenis_sampah'],
                Angka::rupiah($subtotal),
                Setoran::STATUS_MENUNGGU,
            ));
    }

    /**
     * Membaca "12,5" dan "4.500" sebagai angka, bukan teks.
     */
    private function angkaDesimal(mixed $nilai, bool $buangTitikRibuan = false): mixed
    {
        if (! is_string($nilai) || trim($nilai) === '') {
            return $nilai;
        }

        $bersih = str_replace(' ', '', trim($nilai));

        if ($buangTitikRibuan) {
            $bersih = str_replace('.', '', $bersih);
        }

        return str_replace(',', '.', $bersih);
    }
}
