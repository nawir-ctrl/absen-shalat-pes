@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h3 class="text-xl font-semibold">Rekap Absensi Kegiatan Tambahan</h3>
        <p class="text-sm text-gray-500">Lihat rekap absensi kegiatan tambahan berdasarkan filter.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form action="{{ route('absensi-kegiatan-tambahan.rekap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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

            <div>
                <label for="kegiatan_tambahan_id" class="block text-sm font-medium mb-2">Kegiatan</label>
                <select name="kegiatan_tambahan_id" id="kegiatan_tambahan_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    <option value="">Semua Kegiatan</option>
                    @foreach($kegiatanTambahans as $item)
                        <option value="{{ $item->id }}" {{ (string) $kegiatanTambahanId === (string) $item->id ? 'selected' : '' }}>
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
        <div class="mb-6">
            <a href="{{ route('absensi-kegiatan-tambahan.export', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId,
                'kegiatan_tambahan_id' => $kegiatanTambahanId
            ]) }}"
            class="inline-block bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700">
                Export Excel
            </a>

            <a href="{{ route('absensi-kegiatan-tambahan.pdf', [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'kelas_id' => $kelasId,
                'kegiatan_tambahan_id' => $kegiatanTambahanId
            ]) }}"
            target="_blank"
            class="inline-block bg-red-600 text-white px-4 py-2.5 rounded-lg hover:bg-red-700">
                Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
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
                            <td class="px-4 py-3">{{ $item->kegiatanTambahan->nama ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->santri->nama ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->santri->kelas->nama ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($item->status === 'hadir')
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Hadir</span>
                                @elseif($item->status === 'izin')
                                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Izin</span>
                                @elseif($item->status === 'sakit')
                                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">Sakit</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Alpha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data rekap absensi kegiatan.
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