<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bank', 30)->unique();
            $table->string('nama_bank', 100);
            $table->string('penanggung_jawab', 100);
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('jam_operasional', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_sampah');
    }
};
