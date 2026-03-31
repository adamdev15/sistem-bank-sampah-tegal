<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'bank_sampah_master_id',
        'last_login_at',
        'password_changed_at',
        'is_temporary_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'is_temporary_password' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            $user->last_login_at = now();
        });
    }

    /**
     * Get the bank sampah master associated with the user.
     */
    public function bankSampahMaster()
    {
        return $this->belongsTo(BankSampahMaster::class);
    }

    /**
     * Get the aktivitas logs for the user.
     */
    public function aktivitasLogs()
    {
        return $this->hasMany(AktivitasLog::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is bank sampah.
     */
    public function isBankSampah()
    {
        return $this->role === 'bank_sampah';
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /**
     * Check if user has temporary password.
     */
    public function hasTemporaryPassword()
    {
        return $this->is_temporary_password || 
               ($this->password_changed_at && 
                $this->password_changed_at->diffInDays(now()) > 90);
    }

    /**
     * Update last login time.
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Reset password with temporary flag.
     */
    public function resetPassword($password, $isTemporary = true)
    {
        $this->update([
            'password' => Hash::make($password),
            'is_temporary_password' => $isTemporary,
            'password_changed_at' => $isTemporary ? null : now()
        ]);
        
        return $this;
    }

    /**
     * Mark password as changed.
     */
    public function markPasswordAsChanged()
    {
        $this->update([
            'is_temporary_password' => false,
            'password_changed_at' => now()
        ]);
        
        return $this;
    }
}