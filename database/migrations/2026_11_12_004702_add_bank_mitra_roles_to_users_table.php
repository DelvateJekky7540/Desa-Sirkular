<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Role bank sampah dan mitra daur ulang masuk sebagai pengguna,
     * supaya keduanya bisa masuk lewat halaman login dan quick login.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'masyarakat', 'bank', 'mitra'])->default('masyarakat')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'masyarakat'])->default('masyarakat')->change();
        });
    }
};
