@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
        <h3 class="text-xl font-semibold mb-1">Edit Kelas</h3>
        <p class="text-sm text-gray-500 mb-6">Perbarui data kelas, kamar, atau kelompok.</p>

        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-sm font-medium mb-2">Nama Kelas</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $kelas->nama) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">{{ old('keterangan', $kelas->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Update
                </button>
                <a href="{{ route('kelas.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection