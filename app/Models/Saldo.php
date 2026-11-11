<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'saldo';

    protected $fillable = [
        'masyarakat_id',
        'jumlah_saldo',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_saldo' => 'decimal:2',
        ];
    }

    public function masyarakat(): BelongsTo
    {
        return $this->belongsTo(Masyarakat::class);
    }

    public function mutasiSaldo(): HasMany
    {
        return $this->hasMany(MutasiSaldo::class);
    }
}
