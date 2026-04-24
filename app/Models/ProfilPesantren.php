<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPesantren extends Model
{
    protected $fillable = [
        'nomor_statistik',
        'nama_pesantren',
        'alamat',
        'nama_pimpinan',
        'nomor_hp',
        'logo',
    ];
}