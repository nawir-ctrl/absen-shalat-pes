@php
    $profilPesantren = \App\Models\ProfilPesantren::first();
    $namaPesantren = $profilPesantren?->nama_pesantren ?? 'Pondok Pesantren';
    $alamatPesantren = $profilPesantren?->alamat ?? 'Alamat pesantren belum diatur';
    $logoPesantren = $profilPesantren?->logo ? asset('storage/' . $profilPesantren->logo) : null;

    // versi aplikasi, edit manual dari sini
    $versiAplikasi = 'v1.1.0';

    // nama developer / pembuat aplikasi, edit manual dari sini
    $developerAplikasi = 'Divisi IT Pesantren';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($title ?? 'Dashboard') . ' - ' . $namaPesantren }}</title>

    @if($logoPesantren)
        <link rel="icon" type="image/png" href="{{ $logoPesantren }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex">
        <aside class="w-72 bg-gradient-to-b from-green-950 via-green-900 to-green-800 text-white p-5 shadow-2xl">
    <div class="mb-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    @if($logoPesantren)
                        <img src="{{ $logoPesantren }}"
                            alt="Logo Pesantren"
                            class="w-14 h-14 object-contain rounded-2xl bg-white p-1.5 shadow-sm">
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-white font-bold text-lg">
                            PP
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    
                    <h1 class="mt-1 text-base font-bold leading-tight text-white">
                        {{ $namaPesantren }}
                    </h1>

                    <div class="mt-3 text-green-100/90">
    <p class="text-xs leading-relaxed">
        <span class="opacity-70">📍</span>
        {{ $alamatPesantren }}
    </p>
</div>
                </div>
            </div>
        </div>
    </div>

    <nav class="space-y-6 text-sm">
        <div>
            <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-green-200/80">
                Utama
            </p>

            <div class="space-y-1.5">
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8L5 10m14 0l-5-5m5 5v10a1 1 0 01-1 1h-3m-6 0H6a1 1 0 01-1-1V10" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
            <div>
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-green-200/80">
                    Master Data
                </p>

                <div class="space-y-1.5">
                    <a href="{{ route('users.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('users.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2m12 0H7m10-11a3 3 0 11-6 0 3 3 0 016 0zm-8 3a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Kelola User</span>
                    </a>

                    <a href="{{ route('profil-pesantren.show') }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('profil-pesantren.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('profil-pesantren.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Profil Pesantren</span>
                    </a>

                    <a href="{{ route('kelas.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('kelas.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('kelas.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14zm0 0v6" />
                        </svg>
                        <span class="font-medium">Data Kelas</span>
                    </a>

                    <a href="{{ route('santri.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('santri.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('santri.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Data Santri</span>
                    </a>

                    <a href="{{ route('kegiatan-tambahan.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('kegiatan-tambahan.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('kegiatan-tambahan.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>
                        <span class="font-medium">Kegiatan Tambahan</span>
                    </a>
                </div>
            </div>
        @endif

        <div>
            <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-green-200/80">
                Absensi
            </p>

            <div class="space-y-1.5">
                <a href="{{ route('absensi-shalat.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('absensi-shalat.index') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('absensi-shalat.index') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-medium">Absensi Shalat</span>
                </a>

                <a href="{{ route('absensi-kegiatan-tambahan.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('absensi-kegiatan-tambahan.index') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('absensi-kegiatan-tambahan.index') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a4 4 0 014-4h6m-6 0l3-3m-3 3l3 3M5 7h14M5 12h5M5 17h3" />
                    </svg>
                    <span class="font-medium">Absensi Kegiatan</span>
                </a>
            </div>
        </div>

        <div>
            <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-green-200/80">
                Rekapitulasi
            </p>

            <div class="space-y-1.5">

                <a href="{{ route('absensi-shalat.peringkat') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('absensi-shalat.peringkat') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('absensi-shalat.peringkat') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-6h4v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2h-3.5l-1-2h-3l-1 2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Top 5 Shalat</span>
                    </a>
                    
                <a href="{{ route('absensi-shalat.rekap') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('absensi-shalat.rekap') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('absensi-shalat.rekap') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 3a1 1 0 011 1v8a1 1 0 01-2 0V4a1 1 0 011-1zm0 0a8 8 0 108 8h-8V3z" />
                    </svg>
                    <span class="font-medium">Rekap Shalat</span>
                </a>

                <a href="{{ route('absensi-kegiatan-tambahan.rekap') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('absensi-kegiatan-tambahan.rekap') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('absensi-kegiatan-tambahan.rekap') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-6a2 2 0 012-2h8M7 7h10M7 12h6M7 17h.01" />
                    </svg>
                    <span class="font-medium">Rekap Kegiatan</span>
                </a>

                <a href="{{ route('rekap-santri.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all {{ request()->routeIs('rekap-santri.*') ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/10' : 'text-green-50 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('rekap-santri.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2m12 0H7m8-11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium">Rekap Per Santri</span>
                </a>
            </div>
        </div>
    </nav>
</aside>

        <main class="flex-1 p-6">
            <header class="mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div class="hidden sm:flex w-11 h-11 rounded-2xl bg-green-100 text-green-700 items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-green-700">
                                        Sistem Informasi Pesantren
                                    </p>
                                    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                                        Sistem Absensi {{ $namaPesantren }}
                                    </h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Kelola absensi shalat dan kegiatan santri dengan lebih tertib dan efisien.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                <div class="bg-gray-50 border border-gray-20 rounded-2xl px-4 py-3 text-left min-w-[100px]">
                                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                    <div class="mt-1 flex items-center justify-end gap-2">
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-medium text-green-700">
                                            {{ auth()->user()->role }}
                                        </span>
                                        
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center rounded-xl bg-red-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-red-600">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-100 text-red-800 px-4 py-3 border border-red-200">
                    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')

            <footer class="mt-10">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        

                        <div class="text-left md:text-left">
                            <p class="text-xs text-gray-500">
                                &copy; {{ date('Y') }} {{ $namaPesantren }}. All rights reserved.
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Versi <span class="font-semibold text-gray-700">{{ $versiAplikasi }}</span>
                                <span class="mx-1 text-gray-300">•</span>
                                Dikembangkan oleh <span class="font-semibold text-gray-700">{{ $developerAplikasi }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>