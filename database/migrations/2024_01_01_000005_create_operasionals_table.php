<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sampah_master_id')->constrained('bank_sampah_masters')->onDelete('cascade');
            $table->integer('tenaga_kerja_laki')->default(0);
            $table->integer('tenaga_kerja_perempuan')->default(0);
            $table->integer('nasabah_laki')->default(0);
            $table->integer('nasabah_perempuan')->default(0);
            $table->decimal('omset', 15, 2)->default(0);
            $table->string('tempat_penjualan', 200)->nullable();
            $table->text('kegiatan_pengelolaan')->nullable();
            $table->text('produk_daur_ulang')->nullable();
            $table->enum('buku_tabungan', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('sistem_pencatatan', ['Manual', 'Digital', 'Kedua-duanya'])->default('Manual');
            $table->enum('timbangan', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('alat_pengangkut', ['Ya', 'Tidak'])->default('Tidak');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasionals');
    }
};