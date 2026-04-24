<?php

namespace App\Http\Controllers;

use App\Models\ProfilPesantren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPesantrenController extends Controller
{
    public function show()
    {
        $profil = ProfilPesantren::first();

        if (!$profil) {
            $profil = ProfilPesantren::create([
                'nama_pesantren' => 'Pondok Pesantren',
            ]);
        }

        return view('profil-pesantren.show', compact('profil'));
    }

    public function edit()
    {
        $profil = ProfilPesantren::first();

        if (!$profil) {
            $profil = ProfilPesantren::create([
                'nama_pesantren' => 'Pondok Pesantren',
            ]);
        }

        return view('profil-pesantren.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nomor_statistik' => 'nullable|string|max:255',
            'nama_pesantren' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nama_pimpinan' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:30',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profil = ProfilPesantren::first();

        if (!$profil) {
            $profil = new ProfilPesantren();
        }

        if ($request->hasFile('logo')) {
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }

            $validated['logo'] = $request->file('logo')->store('logo-pesantren', 'public');
        }

        $profil->fill($validated);
        $profil->save();

        return redirect()
            ->route('profil-pesantren.show')
            ->with('success', 'Profil pesantren berhasil diperbarui.');
    }
}