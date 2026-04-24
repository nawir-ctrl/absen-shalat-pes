@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h3 class="text-2xl font-bold">Dashboard Admin</h3>

        @auth
            <p class="text-sm text-gray-500">
                Selamat datang, {{ auth()->user()->name }}. Anda login sebagai <strong>Admin</strong>.
            </p>
        @else
            <p class="text-sm text-gray-500">
                Selamat datang di Dashboard Admin.
            </p>
        @endauth
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total User</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalUser }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Santri</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSantri }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Santri Aktif</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSantriAktif }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Santri Nonaktif</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSantriNonaktif }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Kelas</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalKelas }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Kegiatan Aktif</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalKegiatan }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Absensi Shalat Hari Ini</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalAbsensiShalatHariIni }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Absensi Kegiatan Hari Ini</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalAbsensiKegiatanHariIni }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="text-lg font-semibold mb-3">Ringkasan Alpha Hari Ini</h4>
            <div class="space-y-2 text-sm text-gray-700">
                <p>Alpha shalat: <strong>{{ $absensiShalatAlphaHariIni }}</strong></p>
                <p>Alpha kegiatan: <strong>{{ $absensiKegiatanAlphaHariIni }}</strong></p>
                <p>Total alpha: <strong>{{ $absensiShalatAlphaHariIni + $absensiKegiatanAlphaHariIni }}</strong></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="text-lg font-semibold mb-3">Akses Cepat Admin</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('users.index') }}" class="bg-green-700 text-white px-4 py-3 rounded-lg hover:bg-green-800 text-center">
                    Kelola User
                </a>
                <a href="{{ route('kelas.index') }}" class="bg-gray-700 text-white px-4 py-3 rounded-lg hover:bg-gray-800 text-center">
                    Data Kelas
                </a>
                <a href="{{ route('santri.index') }}" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 text-center">
                    Data Santri
                </a>
                <a href="{{ route('kegiatan-tambahan.index') }}" class="bg-amber-600 text-white px-4 py-3 rounded-lg hover:bg-amber-700 text-center">
                    Kegiatan Tambahan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection