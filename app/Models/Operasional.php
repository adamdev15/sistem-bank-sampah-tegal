<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operasional extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bank_sampah_master_id',
        // Data utama
        'tenaga_kerja_laki',
        'tenaga_kerja_perempuan', 
        'nasabah_laki',
        'nasabah_perempuan',
        'omset',
        // Tempat penjualan
        'tempat_penjualan',
        'tempat_penjualan_lainnya',
        // Kegiatan & produk
        'kegiatan_pengelolaan',
        'produk_daur_ulang',
        // Buku tabungan
        'buku_tabungan',
        // Sistem pencatatan
        'sistem_pencatatan',
        // Timbangan
        'timbangan',
        // Alat pengangkut
        'alat_pengangkut',
        'alat_pengangkut_lainnya'
    ];

    protected $casts = [
        'omset' => 'decimal:2'
    ];

    // ========== RELATIONSHIPS ==========
    public function bankSampahMaster()
    {
        return $this->belongsTo(BankSampahMaster::class);
    }

    // ========== ACCESSORS (untuk display) ==========
    
    // 1-4. Data dari bank_sampah_masters (melalui relationship)
    public function getNamaKecamatanAttribute()
    {
        return $this->bankSampahMaster->kecamatan->nama_kecamatan ?? '-';
    }
    
    public function getNamaKelurahanAttribute()
    {
        return $this->bankSampahMaster->kelurahan->nama_kelurahan ?? '-';
    }
    
    public function getRwAttribute()
    {
        return $this->bankSampahMaster->rw ?? '-';
    }
    
    public function getNamaBankSampahAttribute()
    {
        return $this->bankSampahMaster->nama_bank_sampah ?? '-';
    }
    
    // 10. Tempat penjualan label
    public function getTempatPenjualanLabelAttribute()
    {
        $labels = [
            'bank_sampah_induk' => 'Bank Sampah Induk',
            'pengepul' => 'Pengepul',
            'lainnya' => 'Lainnya'
        ];
        
        if ($this->tempat_penjualan == 'lainnya' && $this->tempat_penjualan_lainnya) {
            return $this->tempat_penjualan_lainnya;
        }
        
        return $labels[$this->tempat_penjualan] ?? $this->tempat_penjualan;
    }
    
    // 13. Buku tabungan label
    public function getBukuTabunganLabelAttribute()
    {
        return $this->buku_tabungan == 'ada' ? 'Ada' : 'Tidak Ada';
    }
    
    // 14. Sistem pencatatan label
    public function getSistemPencatatanLabelAttribute()
    {
        return $this->sistem_pencatatan; // Sudah berupa string
    }
    
    // 15. Timbangan label
    public function getTimbanganLabelAttribute()
    {
        $labels = [
            'tidak_ada' => 'Tidak Ada',
            'timbangan_gantung' => 'Timbangan Gantung',
            'timbangan_digital' => 'Timbangan Digital',
            'timbangan_posyandu' => 'Timbangan Posyandu',
            'timbangan_duduk' => 'Timbangan Duduk'
        ];
        
        return $labels[$this->timbangan] ?? $this->timbangan;
    }
    
    // 16. Alat pengangkut label
    public function getAlatPengangkutLabelAttribute()
    {
        $labels = [
            'Tidak_ada' => 'Tidak Ada',
            'Becak' => 'Becak',
            'Gerobak' => 'Gerobak',
            'Tossa' => 'Tossa',
            'Lainnya' => 'Lainnya'
        ];
        
        if ($this->alat_pengangkut == 'Lainnya' && $this->alat_pengangkut_lainnya) {
            return $this->alat_pengangkut_lainnya;
        }
        
        return $labels[$this->alat_pengangkut] ?? $this->alat_pengangkut;
    }
    
    // ========== CALCULATED ATTRIBUTES ==========
    
    // Total tenaga kerja (5+6)
    public function getTotalTenagaKerjaAttribute()
    {
        return $this->tenaga_kerja_laki + $this->tenaga_kerja_perempuan;
    }
    
    // Total nasabah (7+8)
    public function getTotalNasabahAttribute()
    {
        return $this->nasabah_laki + $this->nasabah_perempuan;
    }
    
    // Omset formatted
    public function getOmsetFormattedAttribute()
    {
        return 'Rp ' . number_format($this->omset, 0, ',', '.');
    }
    
    // ========== HELPER METHODS UNTUK FORM ==========
    
    public static function getTempatPenjualanOptions()
    {
        return [
            'bank_sampah_induk' => 'Bank Sampah Induk',
            'pengepul' => 'Pengepul',
            'lainnya' => 'Lainnya (sebutkan)'
        ];
    }
    
    public static function getBukuTabunganOptions()
    {
        return [
            'ada' => 'Ada',
            'tidak_ada' => 'Tidak Ada'
        ];
    }
    
    public static function getSistemPencatatanOptions()
    {
        return [
            'Manual' => 'Manual',
            'Komputerisasi' => 'Komputerisasi',
            'Aplikasi' => 'Aplikasi'
        ];
    }
    
    public static function getTimbanganOptions()
    {
        return [
            'tidak_ada' => 'Tidak Ada',
            'timbangan_gantung' => 'Timbangan Gantung',
            'timbangan_digital' => 'Timbangan Digital',
            'timbangan_posyandu' => 'Timbangan Posyandu',
            'timbangan_duduk' => 'Timbangan Duduk'
        ];
    }
    
    public static function getAlatPengangkutOptions()
    {
        return [
            'Tidak_ada' => 'Tidak Ada',
            'Becak' => 'Becak',
            'Gerobak' => 'Gerobak',
            'Tossa' => 'Tossa',
            'Lainnya' => 'Lainnya (sebutkan)'
        ];
    }
}