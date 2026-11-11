<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $warga = [
            ['name' => 'Bu Siti Rahayu', 'email' => 'siti.rahayu@warga.desasirkular.test'],
            ['name' => 'Pak Budi Santoso', 'email' => 'budi.santoso@warga.desasirkular.test'],
            ['name' => 'Ibu Rina Wulandari', 'email' => 'rina.wulandari@warga.desasirkular.test'],
            ['name' => 'Mas Dedi Kurniawan', 'email' => 'dedi.kurniawan@warga.desasirkular.test'],
            ['name' => 'Bu Ani Lestari', 'email' => 'ani.lestari@warga.desasirkular.test'],
            ['name' => 'Pak Joko Purnomo', 'email' => 'joko.purnomo@warga.desasirkular.test'],
            ['name' => 'Ibu Sri Handayani', 'email' => 'sri.handayani@warga.desasirkular.test'],
            ['name' => 'Mas Rizky Pratama', 'email' => 'rizky.pratama@warga.desasirkular.test'],
        ];

        foreach ($warga as $data) {
            User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ],
            );
        }
    }
}
