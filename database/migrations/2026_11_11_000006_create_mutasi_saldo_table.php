<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saldo_id')->constrained('saldo')->cascadeOnDelete();
            $table->foreignId('setoran_id')->nullable()->unique()->constrained('setoran')->nullOnDelete();
            $table->enum('tipe', ['masuk', 'keluar', 'koreksi']);
            $table->decimal('nominal', 14, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_saldo');
    }
};
