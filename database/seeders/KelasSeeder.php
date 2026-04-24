<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $dataKelas = [
            ['nama' => 'Kelas 1A', 'keterangan' => 'Santri tingkat 1 ruang A'],
            ['nama' => 'Kelas 1B', 'keterangan' => 'Santri tingkat 1 ruang B'],
            ['nama' => 'Kelas 2A', 'keterangan' => 'Santri tingkat 2 ruang A'],
            ['nama' => 'Kamar Umar', 'keterangan' => 'Asrama putra kamar Umar'],
        ];

        foreach ($dataKelas as $item) {
            Kelas::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}