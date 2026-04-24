@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
        <h3 class="text-xl font-semibold mb-1">Import Data Santri</h3>
        <p class="text-sm text-gray-500 mb-6">
            Upload file Excel atau CSV untuk menambahkan data santri sekaligus.
        </p>

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 rounded-lg bg-blue-50 text-blue-800 px-4 py-4 text-sm">
            <p class="font-semibold mb-2">Petunjuk import:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Download template terlebih dahulu.</li>
                <li>Isi data sesuai kolom yang tersedia.</li>
                <li>Kolom <strong>jenis_kelamin</strong> hanya boleh berisi <strong>L</strong> atau <strong>P</strong>.</li>
                <li>Kolom <strong>status</strong> hanya boleh berisi <strong>aktif</strong> atau <strong>nonaktif</strong>.</li>
                <li>Kolom <strong>kelas</strong> harus sama persis dengan nama kelas di aplikasi.</li>
                <li>Simpan file dalam format xlsx, xls, atau csv lalu upload kembali.</li>
            </ul>
        </div>
            <div class="mb-4">
                <a href="{{ route('santri.import.template') }}"
                class="inline-block bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700">
                    Download Template Excel
                </a>      
        <form action="{{ route('santri.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="file" class="block text-sm font-medium mb-2">File Excel / CSV</label>
                <input type="file" name="file" id="file"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                @error('file')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Import
                </button>
                <a href="{{ route('santri.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection