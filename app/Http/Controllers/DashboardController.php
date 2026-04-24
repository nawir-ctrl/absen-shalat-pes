<?php

namespace App\Http\Controllers;

use App\Models\AbsensiKegiatanTambahan;
use App\Models\AbsensiShalat;
use App\Models\KegiatanTambahan;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->timezone(config('app.timezone'))->toDateString();

        $statusShalatHariIni = [
            'subuh' => AbsensiShalat::whereDate('tanggal', $today)->where('waktu_shalat', 'subuh')->exists(),
            'dzuhur' => AbsensiShalat::whereDate('tanggal', $today)->where('waktu_shalat', 'dzuhur')->exists(),
            'ashar' => AbsensiShalat::whereDate('tanggal', $today)->where('waktu_shalat', 'ashar')->exists(),
            'maghrib' => AbsensiShalat::whereDate('tanggal', $today)->where('waktu_shalat', 'maghrib')->exists(),
            'isya' => AbsensiShalat::whereDate('tanggal', $today)->where('waktu_shalat', 'isya')->exists(),
        ];

        if ($user->role === 'admin') {
            $totalUser = User::count();
            $totalSantri = Santri::count();
            $totalSantriAktif = Santri::where('status', 'aktif')->count();
            $totalSantriNonaktif = Santri::where('status', 'nonaktif')->count();
            $totalKelas = Kelas::count();
            $totalKegiatan = KegiatanTambahan::where('is_active', true)->count();

            $totalAbsensiShalatHariIni = AbsensiShalat::whereDate('tanggal', $today)->count();
            $totalAbsensiKegiatanHariIni = AbsensiKegiatanTambahan::whereDate('tanggal', $today)->count();

            $absensiShalatAlphaHariIni = AbsensiShalat::whereDate('tanggal', $today)
                ->where('status', 'alpha')
                ->count();

            $absensiKegiatanAlphaHariIni = AbsensiKegiatanTambahan::whereDate('tanggal', $today)
                ->where('status', 'alpha')
                ->count();

            return view('dashboard.admin', compact(
                'totalUser',
                'totalSantri',
                'totalSantriAktif',
                'totalSantriNonaktif',
                'totalKelas',
                'totalKegiatan',
                'totalAbsensiShalatHariIni',
                'totalAbsensiKegiatanHariIni',
                'absensiShalatAlphaHariIni',
                'absensiKegiatanAlphaHariIni',
                'statusShalatHariIni'
            ));
        }

        $totalSantriAktif = Santri::where('status', 'aktif')->count();

        $totalAbsensiShalatHariIni = AbsensiShalat::whereDate('tanggal', $today)->count();
        $totalAbsensiKegiatanHariIni = AbsensiKegiatanTambahan::whereDate('tanggal', $today)->count();

        $absensiShalatAlphaHariIni = AbsensiShalat::whereDate('tanggal', $today)
            ->where('status', 'alpha')
            ->count();

        $absensiKegiatanAlphaHariIni = AbsensiKegiatanTambahan::whereDate('tanggal', $today)
            ->where('status', 'alpha')
            ->count();

        return view('dashboard.musyrif', compact(
            'totalSantriAktif',
            'totalAbsensiShalatHariIni',
            'totalAbsensiKegiatanHariIni',
            'absensiShalatAlphaHariIni',
            'absensiKegiatanAlphaHariIni',
            'statusShalatHariIni'
        ));
    }
}