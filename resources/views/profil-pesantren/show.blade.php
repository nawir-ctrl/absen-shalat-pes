@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Profil Pesantren</h3>
            <p class="text-sm text-gray-500">Informasi utama pesantren.</p>
        </div>

        <a href="{{ route('profil-pesantren.edit') }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
            Edit Profil
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="md:col-span-2">
                <p class="text-gray-500 mb-2">Logo Pesantren</p>
                @if($profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}"
                         alt="Logo Pesantren"
                         class="h-24 w-24 object-contain rounded-lg border border-gray-200 p-2 bg-white">
                @else
                    <p class="font-medium">-</p>
                @endif
            </div>

            <div>
                <p class="text-gray-500 mb-1">Nomor Statistik</p>
                <p class="font-medium">{{ $profil->nomor_statistik ?: '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500 mb-1">Nama Pesantren</p>
                <p class="font-medium">{{ $profil->nama_pesantren ?: '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 mb-1">Alamat</p>
                <p class="font-medium">{{ $profil->alamat ?: '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500 mb-1">Nama Pimpinan</p>
                <p class="font-medium">{{ $profil->nama_pimpinan ?: '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500 mb-1">Nomor HP</p>
                <p class="font-medium">{{ $profil->nomor_hp ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection