@extends('layouts.admin')

@section('content')
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold">Kegiatan Tambahan</h3>
                <p class="text-sm text-gray-500">Kelola daftar kegiatan tambahan pondok.</p>
            </div>

            <a href="{{ route('kegiatan-tambahan.create') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                + Tambah Kegiatan
            </a>
        </div>

        <form action="{{ route('kegiatan-tambahan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="keyword" value="{{ $keyword ?? '' }}"
                    placeholder="Cari nama atau deskripsi kegiatan..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    <option value="">Semua Status</option>
                    <option value="1" {{ (string) ($status ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string) ($status ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-gray-700 text-white px-4 py-2.5 rounded-lg hover:bg-gray-800 w-full">
                    Cari
                </button>
                <a href="{{ route('kegiatan-tambahan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left">Deskripsi</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatanTambahans as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $kegiatanTambahans->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->deskripsi ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($item->is_active)
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-200 text-gray-700 text-xs">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('kegiatan-tambahan.edit', $item->id) }}"
                                       class="bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('kegiatan-tambahan.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1.5 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data kegiatan tambahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4 border-t">
            {{ $kegiatanTambahans->links() }}
        </div>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif
@endsection