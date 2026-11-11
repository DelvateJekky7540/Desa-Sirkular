<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSetoran extends Model
{
    use HasFactory;

    protected $table = 'detail_setoran';

    protected $fillable = [
        'setoran_id',
        'jenis_sampah_id',
        'berat',
        'harga_per_kg',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'berat' => 'decimal:2',
            'harga_per_kg' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(Setoran::class);
    }

    public function jenisSampah(): BelongsTo
    {
        return $this->belongsTo(JenisSampah::class);
    }
}
