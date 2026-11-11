<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'masyarakat';

    protected $fillable = [
        'user_id',
        'kode_nasabah',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setoran(): HasMany
    {
        return $this->hasMany(Setoran::class);
    }

    public function saldo(): HasOne
    {
        return $this->hasOne(Saldo::class);
    }
}
