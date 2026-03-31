<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['kecamatan_id', 'nama_kelurahan'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function bankSampahMasters()
    {
        return $this->hasMany(BankSampahMaster::class);
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