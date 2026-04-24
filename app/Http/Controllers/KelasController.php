<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $query = Kelas::query();

        if ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%')
                ->orWhere('keterangan', 'like', '%' . $keyword . '%');
        }

        $kelas = $query->latest()->paginate(10)->withQueryString();

        return view('kelas.index', compact('kelas', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Kelas::create($validated);

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
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
    public function edit(Kelas $kela)
    {
        return view('kelas.edit', ['kelas' => $kela]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kela->update($validated);

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela)
    {
        if ($kela->santris()->count() > 0) {
            return redirect()
                ->route('kelas.index')
                ->with('error', 'Kelas tidak bisa dihapus karena masih dipakai santri.');
        }

        $kela->delete();

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}