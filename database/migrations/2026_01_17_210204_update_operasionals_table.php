<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. DROP TABLE LAMA JIKA ADA
        Schema::dropIfExists('operasionals');
        
        // 2. BUAT TABLE BARU DENGAN STRUKTUR YANG BENAR
        Schema::create('operasionals', function (Blueprint $table) {
            $table->id();
            
            // FOREIGN KEY ke bank_sampah_masters
            $table->foreignId('bank_sampah_master_id')
                  ->constrained('bank_sampah_masters')
                  ->onDelete('cascade');
            
            // ========== DATA UTAMA ==========
            // 5. Tenaga kerja laki-laki 
            $table->integer('tenaga_kerja_laki')->default(0);
            
            // 6. Tenaga kerja perempuan 
            $table->integer('tenaga_kerja_perempuan')->default(0);
            
            // 7. Nasabah laki-laki 
            $table->integer('nasabah_laki')->default(0);
            
            // 8. Nasabah perempuan 
            $table->integer('nasabah_perempuan')->default(0);
            
            // 9. Omset
            $table->decimal('omset', 15, 2)->default(0);
            
            // ========== TEMPAT PENJUALAN ==========
            // 10. Tempat penjualan (bank sampah induk/pengepul/lainnya)
            $table->enum('tempat_penjualan', ['bank_sampah_induk', 'pengepul', 'lainnya'])
                  ->default('pengepul');
            
            // Field untuk "lainnya"
            $table->string('tempat_penjualan_lainnya', 200)
                  ->nullable()
                  ->comment('Diisi jika pilihan "lainnya"');
            
            // ========== KEGIATAN & PRODUK ==========
            // 11. Kegiatan pengelolaan sampah
            $table->text('kegiatan_pengelolaan')
                  ->nullable()
                  ->comment('Deskripsi kegiatan pengelolaan sampah');
            
            // 12. Produk daur ulang/kerajinan
            $table->text('produk_daur_ulang')
                  ->nullable()
                  ->comment('Deskripsi produk daur ulang/kerajinan');
            
            // ========== BUKU TABUNGAN ==========
            // 13. Buku tabungan (ada/tidak ada)
            $table->enum('buku_tabungan', ['ada', 'tidak_ada'])
                  ->default('tidak_ada');
            
            // ========== SISTEM PENCATATAN ==========
            // 14. Sistem pencatatan (Manual/komputerisasi/Aplikasi)
            $table->enum('sistem_pencatatan', ['Manual', 'Komputerisasi', 'Aplikasi'])
                  ->default('Manual');
            
            // ========== TIMBANGAN ==========
            // 15. Timbangan (timbangan gantung/timbangan digital/timbangan posyandu/timbangan duduk/tidak ada)
            $table->enum('timbangan', [
                'tidak_ada',
                'timbangan_gantung',
                'timbangan_digital', 
                'timbangan_posyandu',
                'timbangan_duduk'
            ])->default('tidak_ada');
            
            // ========== ALAT PENGANGKUT ==========
            // 16. Alat pengangkut sampah (Becak/Gerobak/Tossa/Tidak ada/Lainnya)
            $table->enum('alat_pengangkut', [
                'Tidak_ada',
                'Becak',
                'Gerobak', 
                'Tossa',
                'Lainnya'
            ])->default('Tidak_ada');
            
            // Field untuk "Lainnya"
            $table->string('alat_pengangkut_lainnya', 100)
                  ->nullable()
                  ->comment('Diisi jika pilihan "Lainnya"');
            
            // TIMESTAMPS
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 3. ADD INDEXES untuk performa
        Schema::table('operasionals', function (Blueprint $table) {
            $table->index('bank_sampah_master_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasionals');
    }
};