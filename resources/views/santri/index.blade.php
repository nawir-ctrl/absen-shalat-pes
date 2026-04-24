@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold">Data Santri</h3>
            <p class="text-sm text-gray-500">Kelola data santri pondok pesantren.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('santri.import') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Import Santri
            </a>

            <a href="{{ route('santri.create') }}"
               class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                + Tambah Santri
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('santri.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2">
                <input type="text"
                       name="keyword"
                       value="{{ $keyword ?? '' }}"
                       placeholder="Cari nama, NIS, atau wali..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
            </div>

            <div>
                <select name="kelas_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}" {{ (string) ($kelasId ?? '') === (string) $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="per_page"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring focus:ring-green-200">
                    <option value="10" {{ ($perPage ?? '10') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ ($perPage ?? '') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ ($perPage ?? '') == '50' ? 'selected' : '' }}>50</option>
                    <option value="all" {{ ($perPage ?? '') === 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-700 text-white px-4 py-2.5 rounded-lg hover:bg-gray-800 w-full transition">
                    Cari
                </button>

                <a href="{{ route('santri.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-300 text-center transition">
                    Reset
                </a>
            </div>
        </form>

        <p class="text-sm text-gray-500 mt-3">
            Tampilkan data per halaman sesuai pilihan.
        </p>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLE + MASS DELETE --}}
    <form action="{{ route('santri.bulk-delete') }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin menghapus santri yang dipilih?')">
        @csrf
        @method('DELETE')

        <input type="hidden" name="keyword" value="{{ $keyword ?? '' }}">
        <input type="hidden" name="kelas_id" value="{{ $kelasId ?? '' }}">
        <input type="hidden" name="per_page" value="{{ $perPage ?? '10' }}">

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 px-4 py-4 border-b bg-gray-50">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" id="check-all" class="rounded border-gray-300">
                        <span>Pilih Semua</span>
                    </label>
                </div>

                <div>
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Hapus Terpilih
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left whitespace-nowrap w-10">
                                <input type="checkbox" id="check-all-header" class="rounded border-gray-300">
                            </th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">NIS</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Nama</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Kelas</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">JK</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Nama Wali</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">No HP Wali</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($santris as $santri)
                            <tr class="border-t align-top hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox"
                                           name="ids[]"
                                           value="{{ $santri->id }}"
                                           class="row-checkbox rounded border-gray-300">
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($santris instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $santris->firstItem() + $loop->index }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">{{ $santri->nis }}</td>

                                <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $santri->nama }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">{{ $santri->kelas->nama ?? '-' }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">{{ $santri->jenis_kelamin }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">{{ $santri->nama_wali ?? '-' }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">{{ $santri->no_hp_wali ?? '-' }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($santri->status === 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    <details class="relative">
                                        <summary class="list-none cursor-pointer inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition">
                                            <span>Aksi</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>

                                        <div class="absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">
                                            <a href="{{ route('santri.edit', $santri->id) }}"
                                               class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                                Edit
                                            </a>

                                            <form action="{{ route('santri.destroy', $santri->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus data santri?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="10" class="text-center py-6 text-gray-500">
                                    Data santri belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($santris instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="px-4 py-4 border-t">
                    {{ $santris->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAllTop = document.getElementById('check-all');
        const checkAllHeader = document.getElementById('check-all-header');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        function setAllChecked(checked) {
            rowCheckboxes.forEach(function (checkbox) {
                checkbox.checked = checked;
            });
            checkAllTop.checked = checked;
            checkAllHeader.checked = checked;
        }

        if (checkAllTop) {
            checkAllTop.addEventListener('change', function () {
                setAllChecked(this.checked);
            });
        }

        if (checkAllHeader) {
            checkAllHeader.addEventListener('change', function () {
                setAllChecked(this.checked);
            });
        }

        rowCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const total = rowCheckboxes.length;
                const checked = document.querySelectorAll('.row-checkbox:checked').length;
                const allChecked = total > 0 && total === checked;

                checkAllTop.checked = allChecked;
                checkAllHeader.checked = allChecked;
            });
        });
    });
</script>
@endsection