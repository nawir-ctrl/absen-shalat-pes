<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'kelas_id',
        'status',
        'nama_wali',
        'no_hp_wali',
        'alamat',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensiShalats(): HasMany
    {
        return $this->hasMany(AbsensiShalat::class);
    }

    public function absensiKegiatanTambahans(): HasMany
    {
        return $this->hasMany(AbsensiKegiatanTambahan::class);
    }
}