<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'deskripsi',
        'ip_address',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter
    public function scopeModul($query, $modul)
    {
        return $query->where('modul', $modul);
    }

    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('created_at', $tanggal);
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