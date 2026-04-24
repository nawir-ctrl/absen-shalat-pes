@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h3 class="text-xl font-semibold">Absensi Shalat</h3>
        <p class="text-sm text-gray-500">Input absensi shalat berdasarkan tanggal dan waktu shalat.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form action="{{ route('absensi-shalat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="tanggal" class="block text-sm font-medium mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <label for="waktu_shalat" class="block text-sm font-medium mb-2">Waktu Shalat</label>
                <select name="waktu_shalat" id="waktu_shalat"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    @foreach($daftarWaktuShalat as $item)
                        <option value="{{ $item }}" {{ $waktuShalat === $item ? 'selected' : '' }}>
                            {{ ucfirst($item) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="kelas_id" class="block text-sm font-medium mb-2">Kelas</label>
                <select name="kelas_id" id="kelas_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}" {{ (string) $kelasId === (string) $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="keyword" class="block text-sm font-medium mb-2">Cari Santri</label>
                <input type="text" name="keyword" id="keyword" value="{{ $keyword }}"
                    placeholder="Nama atau NIS"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <form action="{{ route('absensi-shalat.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="waktu_shalat" value="{{ $waktuShalat }}">
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
        <input type="hidden" name="keyword" value="{{ $keyword }}">

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <p class="font-medium">
                    Tanggal: {{ $tanggal }} | Waktu Shalat: {{ ucfirst($waktuShalat) }}
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Santri</th>
                            <th class="px-4 py-3 text-left">Kelas</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santris as $index => $santri)
                            @php
                                $dataAbsensi = $absensiTersimpan[$santri->id] ?? null;
                                $statusTerpilih = old("absensi.$index.status", $dataAbsensi->status ?? 'hadir');
                                $keteranganTerpilih = old("absensi.$index.keterangan", $dataAbsensi->keterangan ?? '');
                            @endphp

                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $santri->nama }}
                                    <input type="hidden" name="absensi[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                </td>
                                <td class="px-4 py-3">{{ $santri->kelas->nama ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <select name="absensi[{{ $index }}][status]"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="hadir" {{ $statusTerpilih === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="masbuk" {{ $statusTerpilih === 'masbuk' ? 'selected' : '' }}>Masbuk</option>
                                        <option value="izin" {{ $statusTerpilih === 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="sakit" {{ $statusTerpilih === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="alpha" {{ $statusTerpilih === 'alpha' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="absensi[{{ $index }}][keterangan]"
                                           value="{{ $keteranganTerpilih }}"
                                           placeholder="Opsional"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada santri aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($santris->count() > 0)
            <div class="mt-6">
                <button type="submit" class="bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-800">
                    Simpan Absensi
                </button>
            </div>
        @endif
    </form>
@endsection