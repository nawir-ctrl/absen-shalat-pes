<?php

namespace App\Http\Controllers;

use App\Exports\RekapAbsensiShalatExport;
use App\Exports\RekapRingkasanAbsensiShalatExport;
use App\Models\AbsensiShalat;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfilPesantren;
use Illuminate\Support\Facades\DB;

class AbsensiShalatController extends Controller
{
    private function kelasIdFromRequest(Request $request): ?int
    {
        return $request->filled('kelas_id') ? (int) $request->kelas_id : null;
    }

    /**
     * Kembalikan query Kelas yang boleh diakses user saat ini.
     * Admin/musyrif: semua kelas. Pengurus: hanya kelas yang di-assign.
     */
    private function kelasQuery()
    {
        $user = auth()->user();

        if ($user->isPengurus()) {
            return Kelas::whereHas('pengurus', fn($q) => $q->where('users.id', $user->id));
        }

        return Kelas::query();
    }

    /**
     * Terapkan filter kelas pengurus ke query santri/absensi.
     */
    private function applyKelasFilter($query, ?int $kelasId, bool $isSantriQuery = true): void
    {
        $user = auth()->user();
        $allowedKelasIds = $user->kelasIdDiizinkan();

        if ($isSantriQuery) {
            if ($allowedKelasIds !== null) {
                $query->whereIn('kelas_id', $allowedKelasIds);
            }
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
        } else {
            // Query absensi — filter lewat relasi santri
            $query->whereHas('santri', function ($q) use ($allowedKelasIds, $kelasId) {
                if ($allowedKelasIds !== null) {
                    $q->whereIn('kelas_id', $allowedKelasIds);
                }
                if ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                }
            });
        }
    }

    private function ringkasanShalatQuery($tanggalMulai, $tanggalSelesai, ?int $kelasId, ?array $allowedKelasIds = null)
    {
        return Santri::query()
            ->join('kelas', 'santris.kelas_id', '=', 'kelas.id')
            ->leftJoin('absensi_shalats', function ($join) use ($tanggalMulai, $tanggalSelesai) {
                $join->on('santris.id', '=', 'absensi_shalats.santri_id')
                    ->whereBetween('absensi_shalats.tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->where('santris.status', 'aktif')
            ->when($allowedKelasIds !== null, fn($q) => $q->whereIn('santris.kelas_id', $allowedKelasIds))
            ->when($kelasId, fn($q) => $q->where('santris.kelas_id', $kelasId))
            ->select(
                'santris.id',
                'santris.nis',
                'santris.nama',
                'santris.kelas_id',
                'kelas.nama as kelas_nama',
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'hadir' THEN 1 ELSE 0 END) as jumlah_hadir"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'masbuk' THEN 1 ELSE 0 END) as jumlah_masbuk"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'izin' THEN 1 ELSE 0 END) as jumlah_izin"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'sakit' THEN 1 ELSE 0 END) as jumlah_sakit"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'alpha' THEN 1 ELSE 0 END) as jumlah_alpha"),
                DB::raw('COUNT(absensi_shalats.id) as total_absensi')
            )
            ->groupBy('santris.id', 'santris.nis', 'santris.nama', 'santris.kelas_id', 'kelas.nama')
            ->orderBy('kelas.nama')
            ->orderBy('santris.nama');
    }

    public function index(Request $request)
    {
        $tanggal      = $request->tanggal ?? now()->timezone(config('app.timezone'))->toDateString();
        $waktuShalat  = $request->waktu_shalat ?? 'subuh';
        $kelasId      = $this->kelasIdFromRequest($request);
        $keyword      = $request->keyword;

        $daftarWaktuShalat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        $kelas = $this->kelasQuery()->orderBy('nama')->get();

        $query = Santri::with('kelas')->where('status', 'aktif');
        $this->applyKelasFilter($query, $kelasId);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                  ->orWhere('nis', 'like', '%' . $keyword . '%');
            });
        }

        $santris = $query->orderBy('nama')->get();

        $absensiTersimpan = AbsensiShalat::whereDate('tanggal', $tanggal)
            ->where('waktu_shalat', $waktuShalat)
            ->get()
            ->keyBy('santri_id');

        return view('absensi-shalat.index', compact(
            'tanggal', 'waktuShalat', 'kelasId', 'keyword',
            'kelas', 'daftarWaktuShalat', 'santris', 'absensiTersimpan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'                   => 'required|date',
            'waktu_shalat'              => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
            'absensi'                   => 'required|array',
            'absensi.*.santri_id'       => 'required|exists:santris,id',
            'absensi.*.status'          => 'required|in:hadir,masbuk,izin,sakit,alpha',
            'absensi.*.keterangan'      => 'nullable|string',
        ]);

        // Pastikan pengurus hanya bisa simpan absensi santri di kelasnya
        $user = auth()->user();
        $allowedKelasIds = $user->kelasIdDiizinkan();

        foreach ($validated['absensi'] as $item) {
            if ($allowedKelasIds !== null) {
                $santri = Santri::find($item['santri_id']);
                if (!$santri || !in_array($santri->kelas_id, $allowedKelasIds)) {
                    continue; // skip santri di luar kelas yang diizinkan
                }
            }

            AbsensiShalat::updateOrCreate(
                [
                    'santri_id'    => $item['santri_id'],
                    'tanggal'      => $validated['tanggal'],
                    'waktu_shalat' => $validated['waktu_shalat'],
                ],
                [
                    'status'      => $item['status'],
                    'keterangan'  => $item['keterangan'] ?? null,
                    'user_id'     => auth()->id(),
                ]
            );
        }

        return redirect()
            ->route('absensi-shalat.index', [
                'tanggal'      => $validated['tanggal'],
                'waktu_shalat' => $validated['waktu_shalat'],
                'kelas_id'     => $request->kelas_id,
                'keyword'      => $request->keyword,
            ])
            ->with('success', 'Absensi shalat berhasil disimpan.');
    }

    public function rekap(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);
        $allowedKelasIds = auth()->user()->kelasIdDiizinkan();

        $kelas = $this->kelasQuery()->orderBy('nama')->get();

        $query = AbsensiShalat::with(['santri.kelas', 'user'])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal')
            ->orderBy('waktu_shalat');

        $this->applyKelasFilter($query, $kelasId, false);

        $rekap = $query->paginate(15)->withQueryString();
        $ringkasanSantri = $this->ringkasanShalatQuery($tanggalMulai, $tanggalSelesai, $kelasId, $allowedKelasIds)->get();

        return view('absensi-shalat.rekap', compact(
            'rekap', 'ringkasanSantri', 'tanggalMulai', 'tanggalSelesai', 'kelas', 'kelasId'
        ));
    }

    public function export(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);
        $allowedKelasIds = auth()->user()->kelasIdDiizinkan();

        $namaFile = 'rekap-absensi-shalat-' . $tanggalMulai . '-sampai-' . $tanggalSelesai . '.xlsx';

        return Excel::download(
            new RekapAbsensiShalatExport($tanggalMulai, $tanggalSelesai, $kelasId, $allowedKelasIds),
            $namaFile
        );
    }

    public function exportRingkasan(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);
        $allowedKelasIds = auth()->user()->kelasIdDiizinkan();

        $ringkasan = $this->ringkasanShalatQuery($tanggalMulai, $tanggalSelesai, $kelasId, $allowedKelasIds)->get();
        $namaFile = 'rekap-ringkasan-shalat-' . $tanggalMulai . '-sampai-' . $tanggalSelesai . '.xlsx';

        return Excel::download(new RekapRingkasanAbsensiShalatExport($ringkasan), $namaFile);
    }

    public function pdf(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);

        $profilPesantren = ProfilPesantren::first();

        $query = AbsensiShalat::with(['santri.kelas', 'user'])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal')
            ->orderBy('waktu_shalat');

        $this->applyKelasFilter($query, $kelasId, false);

        $rekap = $query->get();

        $pdf = Pdf::loadView('pdf.absensi-shalat', [
            'rekap'          => $rekap,
            'tanggalMulai'   => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'profilPesantren' => $profilPesantren,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('rekap-absensi-shalat-' . $tanggalMulai . '-sampai-' . $tanggalSelesai . '.pdf');
    }

    public function pdfRingkasan(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);
        $allowedKelasIds = auth()->user()->kelasIdDiizinkan();

        $profilPesantren = ProfilPesantren::first();
        $ringkasanSantri = $this->ringkasanShalatQuery($tanggalMulai, $tanggalSelesai, $kelasId, $allowedKelasIds)->get();

        $pdf = Pdf::loadView('pdf.rekap-absensi-shalat-ringkasan', [
            'ringkasanSantri' => $ringkasanSantri,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'profilPesantren' => $profilPesantren,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('rekap-ringkasan-shalat-' . $tanggalMulai . '-sampai-' . $tanggalSelesai . '.pdf');
    }

    public function peringkat(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? now()->timezone(config('app.timezone'))->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->timezone(config('app.timezone'))->toDateString();
        $kelasId        = $this->kelasIdFromRequest($request);

        $kelas = $this->kelasQuery()->orderBy('nama')->get();

        $user            = auth()->user();
        $allowedKelasIds = $user->kelasIdDiizinkan();

        $peringkatSantri = Santri::query()
            ->with('kelas')
            ->where('santris.status', 'aktif')
            ->when($allowedKelasIds !== null, fn($q) => $q->whereIn('santris.kelas_id', $allowedKelasIds))
            ->when($kelasId, fn($q) => $q->where('santris.kelas_id', $kelasId))
            ->leftJoin('absensi_shalats', function ($join) use ($tanggalMulai, $tanggalSelesai) {
                $join->on('santris.id', '=', 'absensi_shalats.santri_id')
                     ->whereBetween('absensi_shalats.tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->select(
                'santris.id', 'santris.nis', 'santris.nama', 'santris.kelas_id',
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'hadir'  THEN 1 ELSE 0 END) as jumlah_hadir"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'masbuk' THEN 1 ELSE 0 END) as jumlah_masbuk"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'izin'   THEN 1 ELSE 0 END) as jumlah_izin"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'sakit'  THEN 1 ELSE 0 END) as jumlah_sakit"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status = 'alpha'  THEN 1 ELSE 0 END) as jumlah_alpha"),
                DB::raw("COUNT(absensi_shalats.id) as total_absensi"),
                DB::raw("SUM(CASE WHEN absensi_shalats.status IN ('alpha','izin','sakit','masbuk') THEN 1 ELSE 0 END) as total_pelanggaran_ringan")
            )
            ->groupBy('santris.id', 'santris.nis', 'santris.nama', 'santris.kelas_id')
            ->havingRaw('COUNT(absensi_shalats.id) > 0')
            ->orderBy('total_pelanggaran_ringan', 'asc')
            ->orderBy('jumlah_alpha', 'asc')
            ->orderBy('jumlah_masbuk', 'asc')
            ->orderBy('jumlah_izin', 'asc')
            ->orderBy('jumlah_sakit', 'asc')
            ->orderBy('santris.nama', 'asc')
            ->take(5)
            ->get();

        return view('absensi-shalat.peringkat', compact(
            'peringkatSantri', 'tanggalMulai', 'tanggalSelesai', 'kelas', 'kelasId'
        ));
    }
}
