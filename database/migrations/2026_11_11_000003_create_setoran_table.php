<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_setoran', 30)->unique();
            $table->foreignId('masyarakat_id')->constrained('masyarakat')->restrictOnDelete();
            $table->foreignId('bank_sampah_id')->constrained('bank_sampah')->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_setoran');
            $table->decimal('total_berat', 10, 2)->default(0);
            $table->decimal('total_nilai', 14, 2)->default(0);
            $table->enum('status', ['menunggu', 'ditimbang', 'dikonfirmasi', 'dibatalkan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('tanggal_setoran');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran');
    }
};
