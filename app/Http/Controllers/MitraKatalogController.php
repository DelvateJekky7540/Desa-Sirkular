<?php

namespace App\Http\Controllers;

use App\Models\BankSampah;
use App\Models\DetailSetoran;
use App\Models\JenisSampah;
use App\Models\Setoran;
use App\Support\Angka;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class MitraKatalogController extends Controller
{
    public function index(Request $request): View
    {
        $request->merge([
            'berat_min' => Angka::parseDesimal($request->input('berat_min')),
        ]);

        $data = $request->validate([
            'jenis_sampah_id' => ['nullable', 'integer', 'exists:jenis_sampah,id'],
            'berat_min' => ['nullable', 'numeric', 'min:0'],
            'bank' => ['nullable', 'integer', 'exists:bank_sampah,id'],
        ], [
            'jenis_sampah_id.exists' => 'Jenis sampah yang dipilih tidak ditemukan.',
            'berat_min.numeric' => 'Berat minimum harus berupa angka, contoh 50.',
            'berat_min.min' => 'Berat minimum tidak boleh negatif.',
            'bank.exists' => 'Bank sampah yang dipilih tidak ditemukan.',
        ]);

        $filterJenis = isset($data['jenis_sampah_id']) ? (int) $data['jenis_sampah_id'] : null;
        $filterBerat = isset($data['berat_min']) ? (float) $data['berat_min'] : null;
        $filterBank = isset($data['bank']) ? (int) $data['bank'] : null;

        $daftarJenis = JenisSampah::query()->aktif()->orderBy('nama_sampah')->get(['id', 'nama_sampah', 'kategori']);

        try {
            $katalog = $this->queryKatalog($filterJenis, $filterBerat, $filterBank);
            $katalogError = null;
        } catch (Throwable $exception) {
            report($exception);

            $katalog = collect();
            $katalogError = 'Katalog bahan baku gagal dimuat. Coba muat ulang halaman.';
        }

        return view('mitra.katalog', [
            'daftarJenis' => $daftarJenis,
            'filterJenis' => $filterJenis,
            'filterBerat' => $filterBerat,
            'filterBank' => $filterBank,
            'namaJenisTerpilih' => $daftarJenis->firstWhere('id', $filterJenis)?->nama_sampah,
            'namaBankTerpilih' => BankSampah::query()->find($filterBank)?->nama_bank,
            'katalogGrup' => $katalog->groupBy('nama_sampah'),
            'ringkasan' => [
                'jenis' => $katalog->pluck('nama_sampah')->unique()->count(),
                'berat' => (float) $katalog->sum('total_berat'),
                'bank' => $katalog->pluck('bank_sampah_id')->unique()->count(),
            ],
            'katalogError' => $katalogError,
        ]);
    }

    /**
     * Stok bahan baku: agregat rincian setoran berstatus ditimbang,
     * dikelompokkan per jenis sampah lalu per bank sampah.
     *
     * @return Collection<int, DetailSetoran>
     */
    private function queryKatalog(?int $jenisSampahId, ?float $beratMinimum, ?int $bankId): Collection
    {
        return DetailSetoran::query()
            ->selectRaw('detail_setoran.jenis_sampah_id, jenis_sampah.nama_sampah, jenis_sampah.kategori, setoran.bank_sampah_id, bank_sampah.nama_bank, SUM(detail_setoran.berat) AS total_berat, COUNT(DISTINCT setoran.id) AS jumlah_setoran')
            ->join('setoran', 'setoran.id', '=', 'detail_setoran.setoran_id')
            ->join('jenis_sampah', 'jenis_sampah.id', '=', 'detail_setoran.jenis_sampah_id')
            ->join('bank_sampah', 'bank_sampah.id', '=', 'setoran.bank_sampah_id')
            ->where('setoran.status', Setoran::STATUS_DITIMBANG)
            ->when($jenisSampahId !== null, fn ($query) => $query->where('detail_setoran.jenis_sampah_id', $jenisSampahId))
            ->when($bankId !== null, fn ($query) => $query->where('setoran.bank_sampah_id', $bankId))
                        // Batas disisipkan sebagai literal angka: PDO mengirim binding sebagai teks,
            // dan SQLite mengurutkan angka di bawah teks sehingga perbandingan >= selalu salah.
            ->when($beratMinimum !== null, fn ($query) => $query->havingRaw(sprintf(
                'SUM(detail_setoran.berat) >= %.4f',
                $beratMinimum,
            )))
            ->groupBy('detail_setoran.jenis_sampah_id', 'jenis_sampah.nama_sampah', 'jenis_sampah.kategori', 'setoran.bank_sampah_id', 'bank_sampah.nama_bank')
            ->orderBy('jenis_sampah.nama_sampah')
            ->orderBy('bank_sampah.nama_bank')
            ->get();
    }
}
