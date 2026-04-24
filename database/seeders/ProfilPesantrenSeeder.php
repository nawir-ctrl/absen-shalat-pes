<?php

namespace Database\Seeders;

use App\Models\ProfilPesantren;
use Illuminate\Database\Seeder;

class ProfilPesantrenSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPesantren::updateOrCreate(
            ['id' => 1],
            [
                'nomor_statistik' => '5100.00.00.000',
                'nama_pesantren' => 'Pondok Pesantren Attarbiyatussakilah Kendari',
                'alamat' => 'Alamat pesantren',
                'nama_pimpinan' => 'Drs. KH. Muchtar Badawi, MA',
                'nomor_hp' => '081234567890',
            ]
        );
    }
}