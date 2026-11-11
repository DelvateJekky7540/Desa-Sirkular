<?php

namespace App\Support;

class Angka
{
    public static function rupiah(float|int|string $nilai): string
    {
        return 'Rp '.number_format((float) $nilai, 0, ',', '.');
    }

    public static function kg(float|int|string $nilai): string
    {
        $teks = number_format((float) $nilai, 2, ',', '.');
        $teks = rtrim(rtrim($teks, '0'), ',');

        return $teks.' kg';
    }
}
