<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_sampah_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained('kelurahans')->onDelete('cascade');
            $table->string('rw', 10);
            $table->enum('status_terbentuk', ['Sudah', 'Belum'])->default('Sudah');
            $table->string('nama_bank_sampah', 200);
            $table->string('nomor_sk', 100)->nullable();
            $table->string('nama_direktur', 100);
            $table->string('nomor_hp', 20);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_sampah_masters');
    }
};