<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke laporan induk
            $table->foreignId('laporan_id')
                ->constrained('laporans')
                ->onDelete('cascade');

            // Jenis sampah (plastik_keras, plastik_fleksibel, kertas, dll)
            $table->string('jenis_sampah', 50);

            // 🔧 PRESISI DITINGKATKAN
            $table->decimal('jumlah', 12, 3)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_details');
    }
};
