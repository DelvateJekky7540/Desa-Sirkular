<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankSampah extends Model
{
    use HasFactory;

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $table = 'bank_sampah';

    protected $fillable = [
        'kode_bank',
        'nama_bank',
        'penanggung_jawab',
        'no_hp',
        'alamat',
        'latitude',
        'longitude',
        'jam_operasional',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function setoran(): HasMany
    {
        return $this->hasMany(Setoran::class);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AKTIF);
    }
}
