<?php

namespace Database\Seeders;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetoranSeeder extends Seeder
{
    public function run(): void
    {
        $hariIni = now()->startOfDay();
        $kemarin = now()->subDay()->startOfDay();
        $duaHariLalu = now()->subDays(2)->startOfDay();
        $tigaHariLalu = now()->subDays(3)->startOfDay();

        $data = [
            ['waktu' => $hariIni->copy()->setTime(7, 45), 'username' => 'siti', 'kode_bank' => 'BS-001', 'petugas' => null, 'jenis' => 'Botol PET', 'tercatat' => 12.5, 'aktual' => null, 'status' => Setoran::STATUS_MENUNGGU, 'catatan' => 'Botol sudah dipilah, tutup dipisah.'],
            ['waktu' => $hariIni->copy()->setTime(8, 30), 'username' => 'budi', 'kode_bank' => 'BS-001', 'petugas' => null, 'jenis' => 'Kardus', 'tercatat' => 8, 'aktual' => null, 'status' => Setoran::STATUS_MENUNGGU, 'catatan' => null],
            ['waktu' => $hariIni->copy()->setTime(9, 15), 'username' => 'rina', 'kode_bank' => 'BS-001', 'petugas' => null, 'jenis' => 'Kaleng aluminium', 'tercatat' => 3.2, 'aktual' => null, 'status' => Setoran::STATUS_MENUNGGU, 'catatan' => 'Kaleng sudah dikeringkan.'],
            ['waktu' => $hariIni->copy()->setTime(10, 5), 'username' => 'dedi', 'kode_bank' => 'BS-001', 'petugas' => null, 'jenis' => 'Plastik HDPE', 'tercatat' => 6.75, 'aktual' => null, 'status' => Setoran::STATUS_MENUNGGU, 'catatan' => null],
            ['waktu' => $hariIni->copy()->setTime(11, 20), 'username' => 'siti', 'kode_bank' => 'BS-001', 'petugas' => null, 'jenis' => 'Kertas HVS', 'tercatat' => 4, 'aktual' => null, 'status' => Setoran::STATUS_MENUNGGU, 'catatan' => null],

            ['waktu' => $kemarin->copy()->setTime(9, 10), 'username' => 'budi', 'kode_bank' => 'BS-001', 'petugas' => 'hendra', 'jenis' => 'Besi tua', 'tercatat' => 15, 'aktual' => 14.8, 'status' => Setoran::STATUS_DITIMBANG, 'catatan' => null],
            ['waktu' => $kemarin->copy()->setTime(13, 40), 'username' => 'rina', 'kode_bank' => 'BS-001', 'petugas' => 'hendra', 'jenis' => 'Botol PET', 'tercatat' => 9, 'aktual' => 9.2, 'status' => Setoran::STATUS_DITIMBANG, 'catatan' => null],
            ['waktu' => $kemarin->copy()->setTime(15, 5), 'username' => 'dedi', 'kode_bank' => 'BS-001', 'petugas' => 'hendra', 'jenis' => 'Kardus', 'tercatat' => 11, 'aktual' => 11.4, 'status' => Setoran::STATUS_DITIMBANG, 'catatan' => 'Kardus kering.'],

            ['waktu' => $duaHariLalu->copy()->setTime(10, 30), 'username' => 'budi', 'kode_bank' => 'BS-001', 'petugas' => 'hendra', 'jenis' => 'Plastik HDPE', 'tercatat' => 5, 'aktual' => 5, 'status' => Setoran::STATUS_DIBATALKAN, 'catatan' => 'Dibatalkan, plastik bercampur sisa makanan.'],

            ['waktu' => $tigaHariLalu->copy()->setTime(8, 20), 'username' => 'ani', 'kode_bank' => 'BS-001', 'petugas' => 'ratna', 'jenis' => 'Plastik HDPE', 'tercatat' => 7.5, 'aktual' => 7.5, 'status' => Setoran::STATUS_DIKONFIRMASI, 'catatan' => null],
            ['waktu' => $tigaHariLalu->copy()->setTime(9, 5), 'username' => 'joko', 'kode_bank' => 'BS-002', 'petugas' => 'ratna', 'jenis' => 'Besi tua', 'tercatat' => 20, 'aktual' => 20, 'status' => Setoran::STATUS_DIKONFIRMASI, 'catatan' => null],
            ['waktu' => $tigaHariLalu->copy()->setTime(10, 15), 'username' => 'sri', 'kode_bank' => 'BS-001', 'petugas' => 'ratna', 'jenis' => 'Kertas HVS', 'tercatat' => 12, 'aktual' => 12, 'status' => Setoran::STATUS_DIKONFIRMASI, 'catatan' => null],
            ['waktu' => $tigaHariLalu->copy()->setTime(11, 40), 'username' => 'rizky', 'kode_bank' => 'BS-002', 'petugas' => 'ratna', 'jenis' => 'Kaleng aluminium', 'tercatat' => 2.4, 'aktual' => 2.4, 'status' => Setoran::STATUS_DIKONFIRMASI, 'catatan' => null],
            ['waktu' => $tigaHariLalu->copy()->setTime(13, 10), 'username' => 'siti', 'kode_bank' => 'BS-001', 'petugas' => 'ratna', 'jenis' => 'Kardus', 'tercatat' => 6, 'aktual' => 6, 'status' => Setoran::STATUS_DIKONFIRMASI, 'catatan' => null],
        ];

        $pengguna = User::query()->pluck('id', 'username');
        $bank = BankSampah::query()->pluck('id', 'kode_bank');
        $jenisSampah = JenisSampah::query()->get()->keyBy('nama_sampah');
        $masyarakat = Masyarakat::query()
            ->with('user')
            ->get()
            ->keyBy(fn (Masyarakat $item) => $item->user?->username);

        foreach ($data as $baris) {
            $warga = $masyarakat->get($baris['username']);
            $jenis = $jenisSampah->get($baris['jenis']);

            if ($warga === null || $jenis === null) {
                $this->command?->warn('Data setoran dilewati, warga atau jenis sampah tidak ditemukan: '.$baris['username'].' '.$baris['jenis']);

                continue;
            }

            $kode = Setoran::kodeBerikutnya($baris['waktu']);

            if (Setoran::query()->where('kode_setoran', $kode)->exists()) {
                continue;
            }

            $berat = (float) ($baris['aktual'] ?? $baris['tercatat']);
            $harga = (float) $jenis->harga_per_kg;
            $subtotal = round($berat * $harga, 2);

            $setoran = new Setoran([
                'kode_setoran' => $kode,
                'masyarakat_id' => $warga->id,
                'bank_sampah_id' => $bank[$baris['kode_bank']],
                'petugas_id' => $baris['petugas'] === null ? null : $pengguna[$baris['petugas']],
                'tanggal_setoran' => $baris['waktu']->toDateString(),
                'total_berat' => $berat,
                'total_nilai' => $subtotal,
                'status' => $baris['status'] === Setoran::STATUS_DIKONFIRMASI ? Setoran::STATUS_DITIMBANG : $baris['status'],
                'catatan' => $baris['catatan'],
            ]);
            $setoran->created_at = $baris['waktu'];
            $setoran->updated_at = $baris['waktu'];
            $setoran->save();

            $setoran->detailSampah()->create([
                'jenis_sampah_id' => $jenis->id,
                'berat' => $berat,
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);

            if ($baris['status'] === Setoran::STATUS_DIKONFIRMASI) {
                $setoran->konfirmasi();

                $mutasi = $setoran->mutasiSaldo()->first();
                $waktuMutasi = $baris['waktu']->copy()->addMinutes(30);

                $mutasi?->forceFill([
                    'created_at' => $waktuMutasi,
                    'updated_at' => $waktuMutasi,
                ])->save();
            }
        }
    }
}
