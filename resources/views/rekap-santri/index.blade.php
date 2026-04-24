@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold">Rekap per Santri</h3>
            <p class="text-sm text-gray-500">Lihat riwayat absensi shalat dan kegiatan untuk satu santri.</p>
        </div>

        @if($santri)
            <a href="{{ route('rekap-santri.pdf', [
                'santri_id' => $santriId,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai
            ]) }}"
               target="_blank"
               class="inline-flex items-center bg-red-600 text-white px-4 py-2.5 rounded-lg hover:bg-red-700">
                Export PDF
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('rekap-santri.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label for="cari_santri" class="block text-sm font-medium mb-2">Cari Nama Santri</label>
            <input type="text"
                   id="cari_santri"
                   placeholder="Ketik nama atau NIS santri"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label for="santri_id" class="block text-sm font-medium mb-2">Santri</label>
            <select name="santri_id" id="santri_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                <option value="">-- Pilih Santri --</option>
                @foreach($santris as $item)
                    <option value="{{ $item->id }}"
                            data-search="{{ strtolower($item->nama . ' ' . $item->nis) }}"
                            {{ (string) $santriId === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->nama }} - {{ $item->nis }}
                    </option>
                @endforeach
            </select>
        </div>

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

        <div class="flex items-end">
            <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800 w-full">
                Tampilkan
            </button>
        </div>
    </form>
</div>

    @if($santri)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="text-lg font-semibold mb-3">Profil Singkat Santri</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-medium">{{ $santri->nama }}</p>
                </div>
                <div>
                    <p class="text-gray-500">NIS</p>
                    <p class="font-medium">{{ $santri->nis }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-medium">{{ $santri->kelas->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-medium">{{ ucfirst($santri->status) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold mb-4">Ringkasan Absensi Shalat</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-gray-500">Hadir</p>
                        <p class="text-2xl font-bold text-green-700">{{ $ringkasanShalat['hadir'] }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-gray-500">Masbuk</p>
                        <p class="text-2xl font-bold text-orange-700">{{ $ringkasanShalat['masbuk'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-gray-500">Izin</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $ringkasanShalat['izin'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-500">Sakit</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $ringkasanShalat['sakit'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-gray-500">Alpha</p>
                        <p class="text-2xl font-bold text-red-700">{{ $ringkasanShalat['alpha'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold mb-4">Ringkasan Absensi Kegiatan</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-gray-500">Hadir</p>
                        <p class="text-2xl font-bold text-green-700">{{ $ringkasanKegiatan['hadir'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-gray-500">Izin</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $ringkasanKegiatan['izin'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-500">Sakit</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $ringkasanKegiatan['sakit'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-gray-500">Alpha</p>
                        <p class="text-2xl font-bold text-red-700">{{ $ringkasanKegiatan['alpha'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT ABSENSI SHALAT --}}
        <form action="{{ route('rekap-santri.bulk-delete-shalat') }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data absensi shalat yang dipilih?')">
            @csrf
            @method('DELETE')

            <input type="hidden" name="santri_id" value="{{ $santriId }}">
            <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <h4 class="font-semibold">Riwayat Absensi Shalat</h4>

                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" id="check-all-shalat" class="rounded border-gray-300">
                            <span>Pilih Semua</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <span id="selected-count-shalat" class="text-sm text-gray-500">0 dipilih</span>

                            <button type="submit"
                                    id="delete-button-shalat"
                                    disabled
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed hover:bg-red-700">
                                Hapus Shalat Terpilih
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" id="check-all-shalat-header" class="rounded border-gray-300">
                                </th>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Waktu</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-left">Input Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapShalat as $item)
                                <tr class="border-t">
                                    <td class="px-4 py-3">
                                        <input type="checkbox"
                                               name="ids[]"
                                               value="{{ $item->id }}"
                                               class="row-checkbox-shalat rounded border-gray-300">
                                    </td>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $item->tanggal->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($item->waktu_shalat) }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->status === 'hadir')
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Hadir</span>
                                        @elseif($item->status === 'masbuk')
                                            <span class="px-2 py-1 rounded bg-orange-100 text-orange-700 text-xs">Masbuk</span>
                                        @elseif($item->status === 'izin')
                                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Izin</span>
                                        @elseif($item->status === 'sakit')
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">Sakit</span>
                                        @elseif($item->status === 'alpha')
                                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Alpha</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada data absensi shalat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        {{-- RIWAYAT ABSENSI KEGIATAN --}}
        <form action="{{ route('rekap-santri.bulk-delete-kegiatan') }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data absensi kegiatan yang dipilih?')">
            @csrf
            @method('DELETE')

            <input type="hidden" name="santri_id" value="{{ $santriId }}">
            <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <h4 class="font-semibold">Riwayat Absensi Kegiatan Tambahan</h4>

                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" id="check-all-kegiatan" class="rounded border-gray-300">
                            <span>Pilih Semua</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <span id="selected-count-kegiatan" class="text-sm text-gray-500">0 dipilih</span>

                            <button type="submit"
                                    id="delete-button-kegiatan"
                                    disabled
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed hover:bg-red-700">
                                Hapus Kegiatan Terpilih
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" id="check-all-kegiatan-header" class="rounded border-gray-300">
                                </th>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Kegiatan</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-left">Input Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapKegiatan as $item)
                                <tr class="border-t">
                                    <td class="px-4 py-3">
                                        <input type="checkbox"
                                               name="ids[]"
                                               value="{{ $item->id }}"
                                               class="row-checkbox-kegiatan rounded border-gray-300">
                                    </td>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $item->tanggal->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $item->kegiatanTambahan->nama ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->status === 'hadir')
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Hadir</span>
                                        @elseif($item->status === 'izin')
                                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Izin</span>
                                        @elseif($item->status === 'sakit')
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">Sakit</span>
                                        @elseif($item->status === 'alpha')
                                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Alpha</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada data absensi kegiatan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupBulkCheckboxes(topId, headerId, rowClass, buttonId, counterId) {
            const checkAllTop = document.getElementById(topId);
            const checkAllHeader = document.getElementById(headerId);
            const deleteButton = document.getElementById(buttonId);
            const counterText = document.getElementById(counterId);
            const rowCheckboxes = document.querySelectorAll('.' + rowClass);

            function updateUI() {
                const total = rowCheckboxes.length;
                const checked = document.querySelectorAll('.' + rowClass + ':checked').length;
                const allChecked = total > 0 && total === checked;

                if (checkAllTop) checkAllTop.checked = allChecked;
                if (checkAllHeader) checkAllHeader.checked = allChecked;
                if (deleteButton) deleteButton.disabled = checked === 0;
                if (counterText) counterText.textContent = checked + ' dipilih';
            }

            function setAllChecked(checked) {
                rowCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = checked;
                });

                updateUI();
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
                    updateUI();
                });
            });

            updateUI();
        }

        setupBulkCheckboxes(
            'check-all-shalat',
            'check-all-shalat-header',
            'row-checkbox-shalat',
            'delete-button-shalat',
            'selected-count-shalat'
        );

        setupBulkCheckboxes(
            'check-all-kegiatan',
            'check-all-kegiatan-header',
            'row-checkbox-kegiatan',
            'delete-button-kegiatan',
            'selected-count-kegiatan'
        );
    });

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('cari_santri');
        const santriSelect = document.getElementById('santri_id');

        if (searchInput && santriSelect) {
            const allOptions = Array.from(santriSelect.querySelectorAll('option'));

            function filterSantriOptions() {
                const keyword = searchInput.value.toLowerCase().trim();
                const currentValue = santriSelect.value;

                santriSelect.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Pilih Santri --';
                santriSelect.appendChild(defaultOption);

                let matchedCount = 0;

                allOptions.forEach(function (option, index) {
                    if (index === 0) return;

                    const searchText = option.dataset.search || '';

                    if (keyword === '' || searchText.includes(keyword)) {
                        const newOption = option.cloneNode(true);
                        santriSelect.appendChild(newOption);
                        matchedCount++;
                    }
                });

                const stillExists = Array.from(santriSelect.options).some(function (option) {
                    return option.value === currentValue;
                });

                if (stillExists) {
                    santriSelect.value = currentValue;
                } else {
                    santriSelect.value = '';
                }

                if (keyword !== '' && matchedCount === 1) {
                    const availableOptions = Array.from(santriSelect.options).filter(function (option) {
                        return option.value !== '';
                    });

                    if (availableOptions.length === 1) {
                        santriSelect.value = availableOptions[0].value;
                    }
                }
            }

            searchInput.addEventListener('input', filterSantriOptions);
        }
    });
</script>


@endsection