<?php

namespace Database\Seeders;

use App\Models\Setoran;
use App\Models\User;
use App\Support\JenisSampah;
use Illuminate\Database\Seeder;

class SetoranSeeder extends Seeder
{
    public function run(): void
    {
        if (Setoran::query()->exists()) {
            $this->command?->info('Setoran sudah ada, seeder dilewati.');

            return;
        }

        $warga = User::query()->pluck('id', 'name');

        $hariIni = now()->startOfDay();
        $kemarin = now()->subDay()->startOfDay();

        $data = [
            ['waktu' => $hariIni->copy()->setTime(7, 45), 'nama' => 'Bu Siti Rahayu', 'jenis' => 'Botol PET', 'berat' => 12.5, 'catatan' => 'Botol sudah dipilah, tutup dipisah.', 'status' => Setoran::STATUS_MENUNGGU],
            ['waktu' => $hariIni->copy()->setTime(8, 30), 'nama' => 'Pak Budi Santoso', 'jenis' => 'Kardus', 'berat' => 8, 'catatan' => null, 'status' => Setoran::STATUS_MENUNGGU],
            ['waktu' => $hariIni->copy()->setTime(9, 15), 'nama' => 'Ibu Rina Wulandari', 'jenis' => 'Kaleng aluminium', 'berat' => 3.2, 'catatan' => 'Kaleng sudah dikeringkan.', 'status' => Setoran::STATUS_MENUNGGU],
            ['waktu' => $hariIni->copy()->setTime(10, 5), 'nama' => 'Mas Dedi Kurniawan', 'jenis' => 'Plastik HDPE', 'berat' => 6.75, 'catatan' => null, 'status' => Setoran::STATUS_MENUNGGU],
            ['waktu' => $hariIni->copy()->setTime(11, 20), 'nama' => 'Bu Siti Rahayu', 'jenis' => 'Kertas HVS', 'berat' => 4, 'catatan' => null, 'status' => Setoran::STATUS_MENUNGGU],
            ['waktu' => $kemarin->copy()->setTime(9, 10), 'nama' => 'Pak Budi Santoso', 'jenis' => 'Besi tua', 'berat' => 15, 'catatan' => null, 'status' => 'Sudah ditimbang'],
            ['waktu' => $kemarin->copy()->setTime(13, 40), 'nama' => 'Ibu Rina Wulandari', 'jenis' => 'Botol PET', 'berat' => 9, 'catatan' => null, 'status' => 'Sudah ditimbang'],
            ['waktu' => $kemarin->copy()->setTime(15, 5), 'nama' => 'Mas Dedi Kurniawan', 'jenis' => 'Kardus', 'berat' => 11, 'catatan' => 'Kardus kering.', 'status' => 'Sudah ditimbang'],
        ];

        foreach ($data as $baris) {
            $harga = JenisSampah::HARGA[$baris['jenis']];
            $subtotal = round($baris['berat'] * $harga, 2);

            $setoran = new Setoran([
                'user_id' => $warga[$baris['nama']],
                'tanggal' => $baris['waktu']->toDateString(),
                'total_berat' => $baris['berat'],
                'total_nilai' => $subtotal,
                'status' => $baris['status'],
                'catatan' => $baris['catatan'],
            ]);
            $setoran->created_at = $baris['waktu'];
            $setoran->updated_at = $baris['waktu'];
            $setoran->save();

            $setoran->detailSampah()->create([
                'jenis_sampah' => $baris['jenis'],
                'berat' => $baris['berat'],
                'harga_per_kg' => $harga,
                'subtotal' => $subtotal,
            ]);
        }
    }
}
