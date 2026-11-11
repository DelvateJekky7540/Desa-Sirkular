<?php

namespace App\Support;

class JenisSampah
{
    /**
     * Harga acuan bank sampah per kilogram.
     *
     * @var array<string, int>
     */
    public const HARGA = [
        'Botol PET' => 4500,
        'Kardus' => 2500,
        'Kaleng aluminium' => 11000,
        'Kertas HVS' => 2000,
        'Plastik HDPE' => 5000,
        'Besi tua' => 3600,
    ];

    /**
     * @return array<string, int>
     */
    public static function semua(): array
    {
        return self::HARGA;
    }

    /**
     * @return list<string>
     */
    public static function nama(): array
    {
        return array_keys(self::HARGA);
    }
}
