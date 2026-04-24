<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $kelasId = $request->kelas_id;
        $perPage = $request->per_page ?? '10';

        $query = Santri::with('kelas');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('nis', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_wali', 'like', '%' . $keyword . '%');
            });
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($perPage === 'all') {
            $santris = $query->latest()->get();
        } else {
            $santris = $query->latest()->paginate((int) $perPage)->withQueryString();
        }

        $kelas = Kelas::orderBy('nama')->get();

        return view('santri.index', compact('santris', 'keyword', 'kelasId', 'kelas', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();

        return view('santri.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:santris,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,nonaktif',
            'nama_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        Santri::create($validated);

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        $kelas = Kelas::orderBy('nama')->get();

        return view('santri.edit', compact('santri', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:santris,nis,' . $santri->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,nonaktif',
            'nama_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        $santri->update($validated);

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        if ($santri->absensiShalats()->count() > 0 || $santri->absensiKegiatanTambahans()->count() > 0) {
            return redirect()
                ->route('santri.index')
                ->with('error', 'Santri tidak bisa dihapus karena sudah memiliki data absensi.');
        }

        $santri->delete();

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Remove selected resources from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:santris,id',
        ], [
            'ids.required' => 'Pilih minimal satu santri yang ingin dihapus.',
        ]);

        $ids = $request->ids;

        $santris = Santri::withCount(['absensiShalats', 'absensiKegiatanTambahans'])
            ->whereIn('id', $ids)
            ->get();

        $bisaDihapus = $santris->filter(function ($santri) {
            return $santri->absensi_shalats_count == 0 && $santri->absensi_kegiatan_tambahans_count == 0;
        });

        $tidakBisaDihapus = $santris->filter(function ($santri) {
            return $santri->absensi_shalats_count > 0 || $santri->absensi_kegiatan_tambahans_count > 0;
        });

        $jumlahDihapus = 0;

        if ($bisaDihapus->count() > 0) {
            $jumlahDihapus = Santri::whereIn('id', $bisaDihapus->pluck('id'))->delete();
        }

        if ($jumlahDihapus > 0 && $tidakBisaDihapus->count() > 0) {
            return redirect()
                ->route('santri.index', $request->only(['keyword', 'kelas_id', 'per_page']))
                ->with('success', $jumlahDihapus . ' data santri berhasil dihapus.')
                ->with('error', $tidakBisaDihapus->count() . ' data santri tidak bisa dihapus karena sudah memiliki data absensi.');
        }

        if ($jumlahDihapus > 0) {
            return redirect()
                ->route('santri.index', $request->only(['keyword', 'kelas_id', 'per_page']))
                ->with('success', $jumlahDihapus . ' data santri berhasil dihapus.');
        }

        return redirect()
            ->route('santri.index', $request->only(['keyword', 'kelas_id', 'per_page']))
            ->with('error', 'Santri yang dipilih tidak bisa dihapus karena sudah memiliki data absensi.');
    }
}