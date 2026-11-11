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

class WargaSetoranController extends Controller
{
    public function index(): View
    {
        $bankAktif = null;
        $daftarJenis = collect();
        $wargaAktif = null;
        $daftarError = null;

        try {
            $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();

            $daftarJenis = JenisSampah::query()
                ->aktif()
                ->orderBy('kategori')
                ->orderBy('nama_sampah')
                ->get();

            $wargaAktif = $this->masyarakatUntukPengajuan();
        } catch (Throwable $exception) {
            report($exception);

            $daftarError = 'Formulir pengajuan gagal dimuat. Coba muat ulang halaman.';
        }

        return view('warga.setor', [
            'bankAktif' => $bankAktif,
            'daftarJenis' => $daftarJenis,
            'wargaAktif' => $wargaAktif,
            'daftarError' => $daftarError,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $bankAktif = BankSampah::query()->aktif()->orderBy('id')->first();
        $wargaAktif = $this->masyarakatUntukPengajuan();

        if ($bankAktif === null) {
            return redirect()
                ->route('warga.setor')
                ->withErrors(['bank_sampah_id' => 'Bank sampah aktif belum tersedia. Coba lagi nanti.'])
                ->withInput();
        }

        if ($wargaAktif === null) {
            return redirect()
                ->route('warga.setor')
                ->withErrors(['masyarakat_id' => 'Data warga untuk pengajuan belum tersedia. Hubungi pengelola bank sampah.'])
                ->withInput();
        }

        $request->merge([
            'berat' => Angka::parseDesimal($request->input('berat')),
        ]);

        $data = $request->validate([
            'jenis_sampah_id' => ['required', 'integer', Rule::exists('jenis_sampah', 'id')->where('status', JenisSampah::STATUS_AKTIF)],
            'berat' => ['required', 'numeric', 'min:0.1', 'max:500'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'jenis_sampah_id.required' => 'Pilih jenis sampah dulu.',
            'jenis_sampah_id.integer' => 'Jenis sampah yang dipilih tidak valid.',
            'jenis_sampah_id.exists' => 'Jenis sampah belum terdaftar atau sudah nonaktif.',
            'berat.required' => 'Berat sampah belum diisi. Isi dulu sebelum mengirim.',
            'berat.numeric' => 'Berat harus berupa angka, contoh 2,5.',
            'berat.min' => 'Berat minimal 0,1 kg.',
            'berat.max' => 'Berat maksimal 500 kg untuk satu pengajuan.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ]);

        $jenis = JenisSampah::query()
            ->aktif()
            ->findOrFail($data['jenis_sampah_id']);

        $berat = (float) $data['berat'];
        $harga = (float) $jenis->harga_per_kg;
        $subtotal = round($berat * $harga, 2);

        $setoran = DB::transaction(function () use ($bankAktif, $wargaAktif, $data, $jenis, $berat, $harga, $subtotal) {
            $setoran = Setoran::query()->create([
                'kode_setoran' => Setoran::kodeBerikutnya(),
                'masyarakat_id' => $wargaAktif->id,
                'bank_sampah_id' => $bankAktif->id,
                'petugas_id' => null,
                'tanggal_setoran' => now()->toDateString(),
                'total_berat' => $berat,
                'total_nilai' => $subtotal,
                'status' => Setoran::STATUS_MENUNGGU,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $setoran->detailSampah()->create([
                'jenis_sampah_id' => $jenis->id,
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);

            return $setoran;
        });

        return redirect()
            ->route('warga.beranda')
            ->with('status', sprintf(
                'Pengajuan %s tercatat: %s %s. Status: %s. Bawa sampahnya ke %s untuk ditimbang. Perkiraan nilai %s, nilai akhir mengikuti hasil timbang.',
                $setoran->kode_setoran,
                Angka::kg($berat),
                $jenis->nama_sampah,
                $setoran->labelStatus(),
                $bankAktif->nama_bank,
                Angka::rupiah($subtotal),
            ));
    }

    private function masyarakatUntukPengajuan(): ?Masyarakat
    {
        $pengguna = auth()->user();

        if ($pengguna instanceof User) {
            if ($pengguna->role !== User::ROLE_MASYARAKAT) {
                return null;
            }

            return $pengguna->masyarakat()->first();
        }

        return Masyarakat::query()
            ->whereHas('user', fn ($query) => $query->where('username', 'siti'))
            ->first();
    }
}
