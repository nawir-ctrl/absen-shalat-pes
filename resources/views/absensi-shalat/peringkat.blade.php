@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold">Top 5 Santri Terbaik Absensi Shalat</h3>
            <p class="text-sm text-gray-500">
                Menampilkan 5 santri dengan jumlah Alpha, Izin, Sakit, dan Masbuk paling kecil dalam rentang waktu tertentu.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('absensi-shalat.peringkat') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                <input type="date"
                       name="tanggal_mulai"
                       id="tanggal_mulai"
                       value="{{ $tanggalMulai }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium mb-2">Tanggal Selesai</label>
                <input type="date"
                       name="tanggal_selesai"
                       id="tanggal_selesai"
                       value="{{ $tanggalSelesai }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <label for="kelas_id" class="block text-sm font-medium mb-2">Kelas</label>
                <select name="kelas_id"
                        id="kelas_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}" {{ (string) $kelasId === (string) $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800 w-full">
                    Tampilkan
                </button>

                <a href="{{ route('absensi-shalat.peringkat') }}"
                   class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Periode</p>
            <p class="text-lg font-semibold">{{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Jumlah Peringkat Ditampilkan</p>
            <p class="text-lg font-semibold">{{ $peringkatSantri->count() }} Santri</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Penilaian</p>
            <p class="text-lg font-semibold">Alpha + Izin + Sakit + Masbuk Terkecil</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <h4 class="font-semibold">Daftar Top 5 Santri</h4>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Peringkat</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">NIS</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Nama</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Kelas</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Hadir</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Masbuk</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Izin</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Sakit</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Alpha</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Total Pelanggaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peringkatSantri as $item)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($loop->iteration === 1)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">
                                        #1
                                    </span>
                                @elseif($loop->iteration === 2)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                                        #2
                                    </span>
                                @elseif($loop->iteration === 3)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-medium">
                                        #3
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        #{{ $loop->iteration }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->nis }}</td>
                            <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $item->nama }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->kelas->nama ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->jumlah_hadir }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->jumlah_masbuk }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->jumlah_izin }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->jumlah_sakit }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $item->jumlah_alpha }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                    {{ $item->total_pelanggaran_ringan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="10" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data absensi shalat pada rentang waktu tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection