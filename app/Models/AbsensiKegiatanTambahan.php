<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiKegiatanTambahan extends Model
{
    protected $fillable = [
        'santri_id',
        'kegiatan_tambahan_id',
        'tanggal',
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

    public function kegiatanTambahan(): BelongsTo
    {
        return $this->belongsTo(KegiatanTambahan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}