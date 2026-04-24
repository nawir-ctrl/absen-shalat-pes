@extends('layouts.admin')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Alpha Hari Ini</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ $absensiShalatAlphaHariIni + $absensiKegiatanAlphaHariIni }}
            </h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-semibold mb-3">Ringkasan Hari Ini</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p>Alpha shalat hari ini: <strong>{{ $absensiShalatAlphaHariIni }}</strong></p>
                <p>Alpha kegiatan hari ini: <strong>{{ $absensiKegiatanAlphaHariIni }}</strong></p>
                <p>Total input absensi shalat hari ini: <strong>{{ $totalAbsensiShalatHariIni }}</strong></p>
                <p>Total input absensi kegiatan hari ini: <strong>{{ $totalAbsensiKegiatanHariIni }}</strong></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-semibold mb-3">Petunjuk Singkat</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p>1. Isi master data kelas terlebih dahulu.</p>
                <p>2. Tambahkan data santri dan pastikan statusnya aktif.</p>
                <p>3. Tambahkan kegiatan tambahan yang digunakan pondok.</p>
                <p>4. Lakukan input absensi shalat dan kegiatan setiap hari.</p>
                <p>5. Gunakan menu rekap untuk melihat hasil absensi.</p>
            </div>
        </div>
    </div>
@endsection