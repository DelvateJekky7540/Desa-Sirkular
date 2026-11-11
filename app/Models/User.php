<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'username', 'email', 'password', 'role', 'no_hp', 'alamat'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_PETUGAS = 'petugas';

    public const ROLE_MASYARAKAT = 'masyarakat';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Identitas nasabah, hanya ada untuk pengguna berperan masyarakat.
     */
    public function masyarakat(): HasOne
    {
        return $this->hasOne(Masyarakat::class);
    }

    /**
     * Setoran yang ditangani pengguna ini sebagai petugas.
     */
    public function setoranDilayani(): HasMany
    {
        return $this->hasMany(Setoran::class, 'petugas_id');
    }
}
