@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
        <h3 class="text-xl font-semibold mb-1">Edit Kegiatan Tambahan</h3>
        <p class="text-sm text-gray-500 mb-6">Perbarui data kegiatan tambahan pondok.</p>

        <form action="{{ route('kegiatan-tambahan.update', $kegiatanTambahan->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-sm font-medium mb-2">Nama Kegiatan</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $kegiatanTambahan->nama) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">{{ old('deskripsi', $kegiatanTambahan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium mb-2">Status</label>
                <select name="is_active" id="is_active"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    <option value="1" {{ old('is_active', (string) $kegiatanTambahan->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', (string) $kegiatanTambahan->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Update
                </button>
                <a href="{{ route('kegiatan-tambahan.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection