<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapRingkasanAbsensiShalatExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected Collection $ringkasan
    ) {
    }

    public function collection(): Collection
    {
        return $this->ringkasan->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nis' => $item->nis,
                'nama_santri' => $item->nama,
                'kelas' => $item->kelas_nama ?? '-',
                'hadir' => (int) $item->jumlah_hadir,
                'masbuk' => (int) $item->jumlah_masbuk,
                'izin' => (int) $item->jumlah_izin,
                'sakit' => (int) $item->jumlah_sakit,
                'alpa' => (int) $item->jumlah_alpha,
                'total' => (int) $item->total_absensi,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Santri',
            'Kelas',
            'Hadir',
            'Masbuk',
            'Izin',
            'Sakit',
            'Alpa',
            'Total',
        ];
    }
}
