<?php

namespace Database\Seeders;

use App\Models\BankSampah;
use Illuminate\Database\Seeder;

class BankSampahSeeder extends Seeder
{
    public function run(): void
    {
        $bank = [
            [
                'kode_bank' => 'BS-001',
                'nama_bank' => 'Bank Sampah Sumber Rejeki',
                'penanggung_jawab' => 'Pak Hendra Wijaya',
                'no_hp' => '0812-3456-7891',
                'alamat' => 'Balai Desa Sukamaju, Jl. Raya Sukamaju No. 12',
                'latitude' => -7.6833000,
                'longitude' => 110.8333000,
                'jam_operasional' => 'Senin sampai Sabtu, 07.00-15.00',
                'status' => BankSampah::STATUS_AKTIF,
            ],
            [
                'kode_bank' => 'BS-002',
                'nama_bank' => 'Bank Sampah Tunas Hijau',
                'penanggung_jawab' => 'Bu Ratna Sari',
                'no_hp' => '0812-3456-7892',
                'alamat' => 'Dusun Krajan, Desa Sukamaju',
                'latitude' => -7.6851000,
                'longitude' => 110.8369000,
                'jam_operasional' => 'Selasa dan Kamis, 08.00-12.00',
                'status' => BankSampah::STATUS_AKTIF,
            ],
        ];

        foreach ($bank as $data) {
            BankSampah::query()->updateOrCreate(
                ['kode_bank' => $data['kode_bank']],
                $data,
            );
        }
    }
}
