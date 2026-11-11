<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSampah extends Model
{
    use HasFactory;

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $table = 'jenis_sampah';

    protected $fillable = [
        'nama_sampah',
        'kategori',
        'harga_per_kg',
        'satuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_kg' => 'decimal:2',
        ];
    }

    public function detailSetoran(): HasMany
    {
        return $this->hasMany(DetailSetoran::class);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AKTIF);
    }
}
