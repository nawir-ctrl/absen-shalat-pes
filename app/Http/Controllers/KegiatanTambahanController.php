<?php

namespace App\Http\Controllers;

use App\Models\KegiatanTambahan;
use Illuminate\Http\Request;

class KegiatanTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $status = $request->status;

        $query = KegiatanTambahan::query();

        if ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%')
                ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $kegiatanTambahans = $query->latest()->paginate(10)->withQueryString();

        return view('kegiatan-tambahan.index', compact('kegiatanTambahans', 'keyword', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kegiatan-tambahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        KegiatanTambahan::create($validated);

        return redirect()
            ->route('kegiatan-tambahan.index')
            ->with('success', 'Data kegiatan tambahan berhasil ditambahkan.');
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
    public function edit(KegiatanTambahan $kegiatan_tambahan)
    {
        return view('kegiatan-tambahan.edit', [
            'kegiatanTambahan' => $kegiatan_tambahan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KegiatanTambahan $kegiatan_tambahan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $kegiatan_tambahan->update($validated);

        return redirect()
            ->route('kegiatan-tambahan.index')
            ->with('success', 'Data kegiatan tambahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KegiatanTambahan $kegiatan_tambahan)
    {
        if ($kegiatan_tambahan->absensiKegiatanTambahans()->count() > 0) {
            return redirect()
                ->route('kegiatan-tambahan.index')
                ->with('error', 'Kegiatan tidak bisa dihapus karena sudah memiliki data absensi.');
        }

        $kegiatan_tambahan->delete();

        return redirect()
            ->route('kegiatan-tambahan.index')
            ->with('success', 'Data kegiatan tambahan berhasil dihapus.');
    }
}