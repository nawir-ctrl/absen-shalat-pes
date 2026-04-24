<?php

namespace App\Exports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateSantriExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nis',
            'nama',
            'jenis_kelamin',
            'kelas',
            'status',
            'nama_wali',
            'no_hp_wali',
            'alamat',
        ];
    }

    public function array(): array
    {
        $kelasPertama = Kelas::orderBy('nama')->value('nama') ?? 'Kelas 1A';

        return [
            [
                'S001',
                'Ahmad Fauzi',
                'L',
                $kelasPertama,
                'aktif',
                'Bapak Hasan',
                '08123456789',
                'Jl. Mawar No. 1',
            ],
            [
                'S002',
                'Aisyah Rahma',
                'P',
                $kelasPertama,
                'aktif',
                'Ibu Siti',
                '08123456780',
                'Jl. Melati No. 2',
            ],
        ];
    }
}