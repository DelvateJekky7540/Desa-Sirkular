<?php

namespace Database\Seeders;

use App\Models\Masyarakat;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasyarakatSeeder extends Seeder
{
    public function run(): void
    {
        $warga = User::query()
            ->where('role', User::ROLE_MASYARAKAT)
            ->orderBy('id')
            ->get();

        foreach ($warga as $indeks => $user) {
            $masyarakat = Masyarakat::query()->updateOrCreate(
                ['user_id' => $user->id],
                ['kode_nasabah' => 'NS-'.str_pad((string) ($indeks + 1), 3, '0', STR_PAD_LEFT)],
            );

            Saldo::query()->firstOrCreate(
                ['masyarakat_id' => $masyarakat->id],
                ['jumlah_saldo' => 0],
            );
        }
    }
}
