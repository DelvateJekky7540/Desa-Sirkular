<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            ['nama_sampah' => 'Botol PET', 'kategori' => 'Plastik', 'harga_per_kg' => 4500],
            ['nama_sampah' => 'Kardus', 'kategori' => 'Kertas', 'harga_per_kg' => 2500],
            ['nama_sampah' => 'Kaleng aluminium', 'kategori' => 'Logam', 'harga_per_kg' => 11000],
            ['nama_sampah' => 'Kertas HVS', 'kategori' => 'Kertas', 'harga_per_kg' => 2000],
            ['nama_sampah' => 'Plastik HDPE', 'kategori' => 'Plastik', 'harga_per_kg' => 5000],
            ['nama_sampah' => 'Besi tua', 'kategori' => 'Logam', 'harga_per_kg' => 3600],
        ];

        foreach ($jenis as $data) {
            JenisSampah::query()->updateOrCreate(
                ['nama_sampah' => $data['nama_sampah']],
                [
                    'kategori' => $data['kategori'],
                    'harga_per_kg' => $data['harga_per_kg'],
                    'satuan' => 'kg',
                    'status' => JenisSampah::STATUS_AKTIF,
                ],
            );
        }
    }
}
