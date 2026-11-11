<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        $pengguna = [
            [
                'nama' => 'Admin Desa Sukamaju',
                'username' => 'admin',
                'email' => 'admin@desasirkular.test',
                'role' => User::ROLE_ADMIN,
                'no_hp' => '0812-3456-7890',
                'alamat' => 'Kantor Desa Sukamaju, Jl. Raya Sukamaju No. 1',
            ],
            [
                'nama' => 'Pak Hendra Wijaya',
                'username' => 'hendra',
                'email' => 'hendra@desasirkular.test',
                'role' => User::ROLE_PETUGAS,
                'no_hp' => '0812-3456-7891',
                'alamat' => 'Dusun Krajan RT 02, Desa Sukamaju',
            ],
            [
                'nama' => 'Bu Ratna Sari',
                'username' => 'ratna',
                'email' => 'ratna@desasirkular.test',
                'role' => User::ROLE_PETUGAS,
                'no_hp' => '0812-3456-7892',
                'alamat' => 'Dusun Sawahan RT 01, Desa Sukamaju',
            ],
            [
                'nama' => 'Bu Siti Rahayu',
                'username' => 'siti',
                'email' => 'siti.rahayu@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1101',
                'alamat' => 'Dusun Krajan RT 01, Desa Sukamaju',
            ],
            [
                'nama' => 'Pak Budi Santoso',
                'username' => 'budi',
                'email' => 'budi.santoso@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1102',
                'alamat' => 'Dusun Krajan RT 03, Desa Sukamaju',
            ],
            [
                'nama' => 'Ibu Rina Wulandari',
                'username' => 'rina',
                'email' => 'rina.wulandari@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1103',
                'alamat' => 'Dusun Sawahan RT 02, Desa Sukamaju',
            ],
            [
                'nama' => 'Mas Dedi Kurniawan',
                'username' => 'dedi',
                'email' => 'dedi.kurniawan@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1104',
                'alamat' => 'Dusun Sawahan RT 04, Desa Sukamaju',
            ],
            [
                'nama' => 'Bu Ani Lestari',
                'username' => 'ani',
                'email' => 'ani.lestari@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1105',
                'alamat' => 'Dusun Ngaran RT 01, Desa Sukamaju',
            ],
            [
                'nama' => 'Pak Joko Purnomo',
                'username' => 'joko',
                'email' => 'joko.purnomo@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1106',
                'alamat' => 'Dusun Ngaran RT 02, Desa Sukamaju',
            ],
            [
                'nama' => 'Ibu Sri Handayani',
                'username' => 'sri',
                'email' => 'sri.handayani@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1107',
                'alamat' => 'Dusun Ngaran RT 03, Desa Sukamaju',
            ],
            [
                'nama' => 'Mas Rizky Pratama',
                'username' => 'rizky',
                'email' => 'rizky.pratama@warga.desasirkular.test',
                'role' => User::ROLE_MASYARAKAT,
                'no_hp' => '0813-2200-1108',
                'alamat' => 'Dusun Krajan RT 04, Desa Sukamaju',
            ],
        ];

        foreach ($pengguna as $data) {
            User::query()->updateOrCreate(
                ['username' => $data['username']],
                [
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'no_hp' => $data['no_hp'],
                    'alamat' => $data['alamat'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ],
            );
        }

        $this->command?->info('Akun demo: admin, hendra, ratna, siti, budi, rina, dedi, ani, joko, sri, rizky. Kata sandi: password');
    }
}
