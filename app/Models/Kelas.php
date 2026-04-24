<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * Pengurus yang mengampu kelas ini.
     */
    public function pengurus(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_user');
    }
}
