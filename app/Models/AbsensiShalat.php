<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiShalat extends Model
{
    protected $fillable = [
        'santri_id',
        'tanggal',
        'waktu_shalat',
        'status',
        'keterangan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}