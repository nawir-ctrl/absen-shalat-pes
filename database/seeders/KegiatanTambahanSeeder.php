<?php

namespace Database\Seeders;

use App\Models\KegiatanTambahan;
use Illuminate\Database\Seeder;

class KegiatanTambahanSeeder extends Seeder
{
    public function run(): void
    {
        $dataKegiatan = [
            [
                'nama' => "Ngaji Ba'da Maghrib",
                'deskripsi' => 'Kegiatan ngaji rutin setelah shalat Maghrib',
                'is_active' => true,
            ],
            [
                'nama' => 'Diniyah Pagi',
                'deskripsi' => 'Kegiatan pembelajaran diniyah pagi hari',
                'is_active' => true,
            ],
            [
                'nama' => 'Muhadharah',
                'deskripsi' => 'Latihan pidato dan public speaking santri',
                'is_active' => true,
            ],
            [
                'nama' => 'Piket Asrama',
                'deskripsi' => 'Tugas kebersihan dan kerapian asrama',
                'is_active' => true,
            ],
        ];

        foreach ($dataKegiatan as $item) {
            KegiatanTambahan::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
