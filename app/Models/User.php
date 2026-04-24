<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function absensiShalats(): HasMany
    {
        return $this->hasMany(AbsensiShalat::class);
    }

    public function absensiKegiatanTambahans(): HasMany
    {
        return $this->hasMany(AbsensiKegiatanTambahan::class);
    }

    /**
     * Kelas yang diampu oleh user (untuk role pengurus).
     */
    public function kelasDiampu(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_user');
    }

    /**
     * Apakah user adalah pengurus?
     */
    public function isPengurus(): bool
    {
        return $this->role === 'pengurus';
    }

    /**
     * Apakah user adalah admin?
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Apakah user adalah musyrif?
     */
    public function isMusyrif(): bool
    {
        return $this->role === 'musyrif';
    }

    /**
     * Kembalikan daftar kelas_id yang boleh diakses user ini.
     * Admin & musyrif: semua kelas (null = tidak difilter).
     * Pengurus: hanya kelas yang di-assign.
     */
    public function kelasIdDiizinkan(): ?array
    {
        if ($this->role === 'pengurus') {
            return $this->kelasDiampu->pluck('id')->toArray();
        }

        return null; // null berarti akses semua kelas
    }
}
