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
        return self::desimal($nilai).' kg';
    }

    /**
     * Angka desimal gaya Indonesia tanpa satuan, untuk nilai input.
     */
    public static function desimal(float|int|string $nilai): string
    {
        $teks = number_format((float) $nilai, 2, ',', '.');
        $teks = rtrim(rtrim($teks, '0'), ',');

        return $teks === '' ? '0' : $teks;
    }

    /**
     * Membaca "12,5" dan "4.500" sebagai angka, bukan teks.
     */
    public static function parseDesimal(mixed $nilai, bool $buangTitikRibuan = false): mixed
    {
        if (! is_string($nilai) || trim($nilai) === '') {
            return $nilai;
        }

        $bersih = str_replace(' ', '', trim($nilai));

        if ($buangTitikRibuan) {
            $bersih = str_replace('.', '', $bersih);
        }

        return str_replace(',', '.', $bersih);
    }
}
