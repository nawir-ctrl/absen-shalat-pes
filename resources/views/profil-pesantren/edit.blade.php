@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <h3 class="text-2xl font-bold mb-1">Edit Profil Pesantren</h3>
    <p class="text-sm text-gray-500 mb-6">Perbarui informasi utama pesantren.</p>

    <form action="{{ route('profil-pesantren.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Nomor Statistik</label>
            <input type="text" name="nomor_statistik" value="{{ old('nomor_statistik', $profil->nomor_statistik) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nama Pesantren</label>
            <input type="text" name="nama_pesantren" value="{{ old('nama_pesantren', $profil->nama_pesantren) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Alamat</label>
            <textarea name="alamat" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5">{{ old('alamat', $profil->alamat) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nama Pimpinan</label>
            <input type="text" name="nama_pimpinan" value="{{ old('nama_pimpinan', $profil->nama_pimpinan) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nomor HP</label>
            <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $profil->nomor_hp) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Logo Pesantren</label>
            <input type="file" name="logo"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">

            @if($profil->logo)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $profil->logo) }}"
                         alt="Logo Pesantren"
                         class="h-20 w-20 object-contain rounded-lg border border-gray-200 p-1 bg-white">
                </div>
            @endif
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                Simpan
            </button>

            <a href="{{ route('profil-pesantren.show') }}"
               class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection