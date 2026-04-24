@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h3 class="text-xl font-semibold">Rekap Absensi Shalat</h3>
        <p class="text-sm text-gray-500">Lihat rekap absensi shalat berdasarkan rentang tanggal.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form action="{{ route('absensi-shalat.rekap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium mb-2">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggalSelesai }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
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

            <div class="flex items-end">
                <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                    Tampilkan
                </button>
            </div>
        </form>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('absensi-shalat.ringkasan.export', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId
            ]) }}"
            class="inline-block bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700">
                Export Excel Ringkasan
            </a>

            <a href="{{ route('absensi-shalat.ringkasan.pdf', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId
            ]) }}"
            target="_blank"
            class="inline-block bg-red-600 text-white px-4 py-2.5 rounded-lg hover:bg-red-700">
                Export PDF Ringkasan
            </a>

            <a href="{{ route('absensi-shalat.export', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId
            ]) }}"
            class="inline-block bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700">
                Export Excel Detail
            </a>

            <a href="{{ route('absensi-shalat.pdf', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId
            ]) }}"
            target="_blank"
            class="inline-block bg-red-600 text-white px-4 py-2.5 rounded-lg hover:bg-red-700">
                Export PDF Detail
            </a>
        </div>

        
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-4 border-b bg-gray-50">
            <h4 class="font-semibold">Rekap Shalat Per Santri</h4>
            <p class="text-sm text-gray-500">Jumlah hadir, masbuk, izin, sakit, dan alpa berdasarkan kelas lalu nama santri.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-left">Nama Santri</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-left">Hadir</th>
                        <th class="px-4 py-3 text-left">Masbuk</th>
                        <th class="px-4 py-3 text-left">Izin</th>
                        <th class="px-4 py-3 text-left">Sakit</th>
                        <th class="px-4 py-3 text-left">Alpa</th>
                        <th class="px-4 py-3 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ringkasanSantri as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $item->nis }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->kelas_nama ?? '-' }}</td>
                            <td class="px-4 py-3">{{ (int) $item->jumlah_hadir }}</td>
                            <td class="px-4 py-3">{{ (int) $item->jumlah_masbuk }}</td>
                            <td class="px-4 py-3">{{ (int) $item->jumlah_izin }}</td>
                            <td class="px-4 py-3">{{ (int) $item->jumlah_sakit }}</td>
                            <td class="px-4 py-3">{{ (int) $item->jumlah_alpha }}</td>
                            <td class="px-4 py-3 font-semibold">{{ (int) $item->total_absensi }}</td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="10" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data santri aktif untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 py-4 border-b bg-gray-50">
            <h4 class="font-semibold">Detail Absensi Shalat</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-left">Input Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $rekap->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ ucfirst($item->waktu_shalat) }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->santri->nama ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->santri->kelas->nama ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($item->status === 'hadir')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                    Hadir
                                </span>
                            @elseif($item->status === 'masbuk')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
                                    Masbuk
                                </span>
                            @elseif($item->status === 'izin')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                    Izin
                                </span>
                            @elseif($item->status === 'sakit')
                                <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-800">
                                    Sakit
                                </span>
                            @elseif($item->status === 'alpha')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                    Alpha
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                            </td>
                            <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data rekap absensi shalat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4 border-t">
            {{ $rekap->links() }}
        </div>
    </div>
@endsection
