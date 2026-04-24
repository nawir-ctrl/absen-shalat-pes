<?php

namespace App\Exports;

use App\Models\AbsensiKegiatanTambahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiKegiatanExport implements FromCollection, WithHeadings
{
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $kelasId;
    protected $kegiatanTambahanId;
    protected $allowedKelasIds;

    public function __construct($tanggalMulai, $tanggalSelesai, $kelasId = null, $kegiatanTambahanId = null, $allowedKelasIds = null)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->kelasId = $kelasId;
        $this->kegiatanTambahanId = $kegiatanTambahanId;
        $this->allowedKelasIds = $allowedKelasIds;
    }

    public function collection()
    {
        $query = AbsensiKegiatanTambahan::with(['santri.kelas', 'kegiatanTambahan', 'user'])
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai])
            ->orderBy('tanggal');

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

        if ($this->kegiatanTambahanId) {
            $query->where('kegiatan_tambahan_id', $this->kegiatanTambahanId);
        }

        return $query->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal->format('Y-m-d'),
                'kegiatan' => $item->kegiatanTambahan->nama ?? '-',
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
            'Kegiatan',
            'Nama Santri',
            'Kelas',
            'Status',
            'Keterangan',
            'Input Oleh',
        ];
    }
}
