@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
        <h3 class="text-xl font-semibold mb-1">Tambah Santri</h3>
        <p class="text-sm text-gray-500 mb-6">Isi data santri baru.</p>

        <form action="{{ route('santri.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="nis" class="block text-sm font-medium mb-2">NIS</label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    @error('nis')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium mb-2">Nama Santri</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $item)
                            <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_wali" class="block text-sm font-medium mb-2">Nama Wali</label>
                    <input type="text" name="nama_wali" id="nama_wali" value="{{ old('nama_wali') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    @error('nama_wali')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="no_hp_wali" class="block text-sm font-medium mb-2">No. HP Wali</label>
                    <input type="text" name="no_hp_wali" id="no_hp_wali" value="{{ old('no_hp_wali') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    @error('no_hp_wali')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Simpan
                </button>
                <a href="{{ route('santri.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection