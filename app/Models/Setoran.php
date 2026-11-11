<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setoran extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU = 'Menunggu Timbang';

    protected $table = 'setoran';

    protected $fillable = [
        'user_id',
        'tanggal',
        'total_berat',
        'total_nilai',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'total_berat' => 'decimal:2',
            'total_nilai' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailSampah(): HasMany
    {
        return $this->hasMany(DetailSetoran::class);
    }
}
