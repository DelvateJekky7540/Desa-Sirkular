<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')->constrained('setoran')->cascadeOnDelete();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->restrictOnDelete();
            $table->decimal('berat', 10, 2);
            $table->decimal('harga_per_kg', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_setoran');
    }
};
