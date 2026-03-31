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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();

            // Relasi ke bank_sampah_masters
            $table->foreignId('bank_sampah_master_id')
                ->constrained('bank_sampah_masters')
                ->onDelete('cascade');

            // Periode laporan (YYYY-MM-01)
            $table->date('periode');

            // 🔧 PRESISI DITINGKATKAN
            $table->decimal('jumlah_sampah_masuk', 12, 3);
            $table->decimal('jumlah_sampah_terkelola', 12, 3);

            // Jumlah nasabah
            $table->integer('jumlah_nasabah')->default(0);

            // Status verifikasi laporan
            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'perlu_perbaikan'
            ])->default('menunggu_verifikasi');

            // Catatan dari admin
            $table->text('catatan_verifikasi')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
