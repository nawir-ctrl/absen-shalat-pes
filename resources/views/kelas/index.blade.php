@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl font-semibold">Data Kelas</h3>
            <p class="text-sm text-gray-500">Kelola kelas, kamar, atau kelompok santri.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-3">
            <form action="{{ route('kelas.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="keyword" value="{{ $keyword ?? '' }}"
                    placeholder="Cari kelas..."
                    class="border border-gray-300 rounded-lg px-4 py-2.5 w-full md:w-64">
                <button type="submit" class="bg-gray-700 text-white px-4 py-2.5 rounded-lg hover:bg-gray-800">
                    Cari
                </button>
            </form>

            <a href="{{ route('kelas.create') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                + Tambah Kelas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Kelas</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-left">Jumlah Santri</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $kelas->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $item->santris()->count() }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('kelas.edit', $item->id) }}"
                                       class="bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('kelas.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                Belum ada data kelas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                <div class="px-4 py-4 border-t">
                    {{ $kelas->links() }}
                </div>
    </div>
@endsection