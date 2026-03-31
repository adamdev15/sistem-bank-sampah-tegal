<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankSampahMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kecamatan_id',
        'kelurahan_id',
        'rw',
        'status_terbentuk',
        'nama_bank_sampah',
        'nomor_sk',
        'nama_direktur',
        'nomor_hp',
        'keterangan'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function operasional()
    {
        return $this->hasOne(Operasional::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
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