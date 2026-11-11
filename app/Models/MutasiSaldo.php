<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiSaldo extends Model
{
    use HasFactory;

    public const TIPE_MASUK = 'masuk';

    public const TIPE_KELUAR = 'keluar';

    public const TIPE_KOREKSI = 'koreksi';

    protected $table = 'mutasi_saldo';

    protected $fillable = [
        'saldo_id',
        'setoran_id',
        'tipe',
        'nominal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function saldo(): BelongsTo
    {
        return $this->belongsTo(Saldo::class);
    }

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(Setoran::class);
    }
}
