<?php

namespace App\Exports;

use App\Models\AbsensiShalat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiShalatExport implements FromCollection, WithHeadings
{
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $kelasId;
    protected $allowedKelasIds;

    public function __construct($tanggalMulai, $tanggalSelesai, $kelasId = null, $allowedKelasIds = null)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->kelasId = $kelasId;
        $this->allowedKelasIds = $allowedKelasIds;
    }

    public function collection()
    {
        $query = AbsensiShalat::with(['santri.kelas', 'user'])
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai])
            ->orderBy('tanggal')
            ->orderBy('waktu_shalat');

        if ($this->allowedKelasIds !== null || $this->kelasId) {
            $query->whereHas('santri', function ($q) {
                if ($this->allowedKelasIds !== null) {
                    $q->whereIn('kelas_id', $this->allowedKelasIds);
                }

                if ($this->kelasId) {
                    $q->where('kelas_id', $this->kelasId);
                }
            });
        }

        return $query->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal->format('Y-m-d'),
                'waktu_shalat' => ucfirst($item->waktu_shalat),
                'nama_santri' => $item->santri->nama ?? '-',
                'kelas' => $item->santri->kelas->nama ?? '-',
                'status' => ucfirst($item->status),
                'keterangan' => $item->keterangan ?? '-',
                'input_oleh' => $item->user->name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Waktu Shalat',
            'Nama Santri',
            'Kelas',
            'Status',
            'Keterangan',
            'Input Oleh',
        ];
    }
}
