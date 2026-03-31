<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bank_sampah_master_id',
        'periode',
        'jumlah_sampah_masuk',
        'jumlah_sampah_terkelola',
        'jumlah_nasabah',
        'status',
        'catatan_verifikasi'
    ];

    protected $casts = [
        'periode' => 'date'
    ];

    public function bankSampahMaster()
    {
        return $this->belongsTo(BankSampahMaster::class);
    }

    public function details()
    {
        return $this->hasMany(LaporanDetail::class);
    }

    public function scopePeriode($query, $tahun, $bulan)
    {
        return $query->whereYear('periode', $tahun)
                     ->whereMonth('periode', $bulan);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Get bulan dan tahun
    public function getBulanAttribute()
    {
        return $this->periode->format('m');
    }

    public function getTahunAttribute()
    {
        return $this->periode->format('Y');
    }

    public function getNamaBulanAttribute()
    {
        return $this->periode->translatedFormat('F');
    }
    // Scope untuk hanya data aktif (tidak terhapus)
public function scopeActive($query)
{
    return $query->whereNull('deleted_at');
}

// Scope untuk data terhapus
public function scopeTrashed($query)
{
    return $query->whereNotNull('deleted_at');
}

// Scope dengan data terhapus
public function scopeWithTrashed($query)
{
    return $query->withoutGlobalScope('softDeletes');
}
}