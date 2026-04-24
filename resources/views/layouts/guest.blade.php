@php
    $profilPesantren = \App\Models\ProfilPesantren::first();
    $namaPesantren = $profilPesantren?->nama_pesantren ?? 'Pondok Pesantren';
    $logoPesantren = $profilPesantren?->logo ? asset('storage/' . $profilPesantren->logo) : null;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $namaPesantren }} - Login</title>

    @if($logoPesantren)
        <link rel="icon" type="image/png" href="{{ $logoPesantren }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="mb-6 text-center">
            @if($logoPesantren)
                <img src="{{ $logoPesantren }}"
                     alt="Logo Pesantren"
                     class="w-20 h-20 object-contain rounded-xl bg-white p-2 shadow mx-auto mb-4">
            @endif

            <h1 class="text-2xl font-bold text-gray-800">{{ $namaPesantren }}</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Absensi</p>
        </div>

        <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md overflow-hidden rounded-xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>