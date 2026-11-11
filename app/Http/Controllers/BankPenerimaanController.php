<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use App\Support\Angka;
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
        $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();
        $riwayatError = null;

        try {
            $riwayat = Setoran::query()
                ->with(['masyarakat.user', 'detailSampah.jenisSampah'])
                ->when($bankAktif !== null, fn ($query) => $query->where('bank_sampah_id', $bankAktif->id))
                ->whereDate('tanggal_setoran', now()->toDateString())
                ->latest('id')
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            $riwayat = collect();
            $riwayatError = 'Riwayat setoran hari ini gagal dimuat. Coba muat ulang halaman.';
        }

        return view('bank.penerimaan', [
            'bankAktif' => $bankAktif,
            'daftarWarga' => Masyarakat::query()
                ->with('user')
                ->whereHas('user', fn ($query) => $query->where('role', User::ROLE_MASYARAKAT))
                ->orderBy('kode_nasabah')
                ->get(),
            'daftarJenis' => JenisSampah::query()->aktif()->orderBy('nama_sampah')->get(),
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
        $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();

        if ($bankAktif === null) {
            return redirect()
                ->route('bank.penerimaan')
                ->withErrors(['bank_sampah_id' => 'Bank sampah aktif belum tersedia. Jalankan seeder dulu sebelum mencatat setoran.']);
        }

        $request->merge([
            'berat' => $this->angkaDesimal($request->input('berat')),
            'harga_per_kg' => $this->angkaDesimal($request->input('harga_per_kg'), true),
        ]);

        $data = $request->validate([
            'masyarakat_id' => ['required', 'integer', Rule::exists('masyarakat', 'id')],
            'jenis_sampah_id' => ['required', 'integer', Rule::exists('jenis_sampah', 'id')->where('status', JenisSampah::STATUS_AKTIF)],
            'berat' => ['required', 'numeric', 'min:0.1', 'max:5000'],
            'harga_per_kg' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'masyarakat_id.required' => 'Pilih warga yang menyetor sampah.',
            'masyarakat_id.exists' => 'Warga yang dipilih tidak ditemukan.',
            'jenis_sampah_id.required' => 'Pilih jenis sampah yang ditimbang.',
            'jenis_sampah_id.exists' => 'Jenis sampah belum terdaftar atau sudah nonaktif.',
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

        $setoran = DB::transaction(function () use ($bankAktif, $data, $berat, $harga, $subtotal) {
            $setoran = Setoran::query()->create([
                'kode_setoran' => Setoran::kodeBerikutnya(),
                'masyarakat_id' => $data['masyarakat_id'],
                'bank_sampah_id' => $bankAktif->id,
                'petugas_id' => null,
                'tanggal_setoran' => now()->toDateString(),
                'total_berat' => $berat,
                'total_nilai' => $subtotal,
                'status' => Setoran::STATUS_MENUNGGU,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $setoran->detailSampah()->create([
                'jenis_sampah_id' => $data['jenis_sampah_id'],
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);

            return $setoran;
        });

        $setoran->load('masyarakat.user', 'detailSampah.jenisSampah');

        return redirect()
            ->route('bank.penerimaan')
            ->with('status', sprintf(
                'Setoran %s tercatat: %s %s senilai %s. Status: %s.',
                $setoran->masyarakat?->user?->nama ?? 'warga',
                Angka::kg($berat),
                $setoran->detailSampah->first()?->jenisSampah?->nama_sampah ?? 'sampah',
                Angka::rupiah($subtotal),
                $setoran->labelStatus(),
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
