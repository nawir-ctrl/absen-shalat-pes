@extends('layouts.admin')

@section('content')
@php
    $today = now()->timezone(config('app.timezone'))->toDateString();
@endphp

<div class="space-y-6">

    <div>
        <h3 class="text-2xl font-bold">Dashboard Musyrif</h3>

        @auth
            <p class="text-sm text-gray-500">
                Selamat datang, {{ auth()->user()->name }}. Anda login sebagai <strong>Musyrif</strong>.
            </p>
        @else
            <p class="text-sm text-gray-500">
                Selamat datang di Dashboard Musyrif.
            </p>
        @endauth
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Santri Aktif</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSantriAktif }}</h3>
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
            <p class="text-sm text-gray-500">Total Alpha Hari Ini</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ $absensiShalatAlphaHariIni + $absensiKegiatanAlphaHariIni }}
            </h3>
        </div>
    </div>

    {{-- SHORTCUT SHALAT --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h4 class="text-lg font-semibold mb-3">Absensi Shalat Hari Ini</h4>
        <p class="text-sm text-gray-500 mb-4">
            Hijau berarti sudah diabsen. Merah berarti belum diabsen.
        </p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <a href="{{ route('absensi-shalat.index', ['tanggal' => $today, 'waktu_shalat' => 'subuh']) }}"
               class="{{ $statusShalatHariIni['subuh'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-3 rounded-lg text-center font-semibold shadow">
                Subuh
            </a>

            <a href="{{ route('absensi-shalat.index', ['tanggal' => $today, 'waktu_shalat' => 'dzuhur']) }}"
               class="{{ $statusShalatHariIni['dzuhur'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-3 rounded-lg text-center font-semibold shadow">
                Dzuhur
            </a>

            <a href="{{ route('absensi-shalat.index', ['tanggal' => $today, 'waktu_shalat' => 'ashar']) }}"
               class="{{ $statusShalatHariIni['ashar'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-3 rounded-lg text-center font-semibold shadow">
                Ashar
            </a>

            <a href="{{ route('absensi-shalat.index', ['tanggal' => $today, 'waktu_shalat' => 'maghrib']) }}"
               class="{{ $statusShalatHariIni['maghrib'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-3 rounded-lg text-center font-semibold shadow">
                Maghrib
            </a>

            <a href="{{ route('absensi-shalat.index', ['tanggal' => $today, 'waktu_shalat' => 'isya']) }}"
               class="{{ $statusShalatHariIni['isya'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-3 rounded-lg text-center font-semibold shadow">
                Isya
            </a>
        </div>
    </div>

    {{-- RINGKASAN + AKSES CEPAT --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="text-lg font-semibold mb-3">Ringkasan Hari Ini</h4>

            <div class="space-y-2 text-sm text-gray-700">
                <p>Alpha shalat: <strong>{{ $absensiShalatAlphaHariIni }}</strong></p>
                <p>Alpha kegiatan: <strong>{{ $absensiKegiatanAlphaHariIni }}</strong></p>
                <p>Total input absensi shalat: <strong>{{ $totalAbsensiShalatHariIni }}</strong></p>
                <p>Total input absensi kegiatan: <strong>{{ $totalAbsensiKegiatanHariIni }}</strong></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="text-lg font-semibold mb-3">Akses Cepat Musyrif</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('absensi-shalat.index') }}"
                   class="bg-green-700 text-white px-4 py-3 rounded-lg hover:bg-green-800 text-center">
                    Absensi Shalat
                </a>

                <a href="{{ route('absensi-kegiatan-tambahan.index') }}"
                   class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 text-center">
                    Absensi Kegiatan
                </a>

                <a href="{{ route('absensi-shalat.rekap') }}"
                   class="bg-gray-700 text-white px-4 py-3 rounded-lg hover:bg-gray-800 text-center">
                    Rekap Shalat
                </a>

                <a href="{{ route('absensi-kegiatan-tambahan.rekap') }}"
                   class="bg-amber-600 text-white px-4 py-3 rounded-lg hover:bg-amber-700 text-center">
                    Rekap Kegiatan
                </a>
            </div>

            <div class="mt-3">
                <a href="{{ route('rekap-santri.index') }}"
                   class="block bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 text-center">
                    Rekap Per Santri
                </a>
            </div>
        </div>
    </div>

</div>
@endsection