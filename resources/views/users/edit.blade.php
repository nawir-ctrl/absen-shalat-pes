@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <h3 class="text-xl font-semibold mb-1">Edit User</h3>
    <p class="text-sm text-gray-500 mb-6">Perbarui data user.</p>

    @if($errors->any())
        <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3 mb-4">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Role</label>
            <select name="role" id="role-select" class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                    onchange="toggleKelasSection()">
                <option value="admin"    {{ old('role', $user->role) === 'admin'    ? 'selected' : '' }}>Admin</option>
                <option value="musyrif"  {{ old('role', $user->role) === 'musyrif'  ? 'selected' : '' }}>Musyrif</option>
                <option value="pengurus" {{ old('role', $user->role) === 'pengurus' ? 'selected' : '' }}>Pengurus</option>
            </select>
        </div>

        {{-- Pilihan kelas, hanya tampil jika role = pengurus --}}
        <div id="kelas-section" class="{{ old('role', $user->role) === 'pengurus' ? '' : 'hidden' }}">
            <label class="block text-sm font-medium mb-2">
                Kelas yang Diampu
                <span class="text-gray-400 font-normal">(bisa pilih lebih dari satu)</span>
            </label>

            @if($kelas->isEmpty())
                <p class="text-sm text-gray-400 italic">Belum ada kelas. Silakan tambah kelas terlebih dahulu.</p>
            @else
                <div class="grid grid-cols-2 gap-2 border border-gray-200 rounded-lg p-3 bg-gray-50">
                    @foreach($kelas as $k)
                        @php
                            $checked = in_array($k->id, old('kelas_ids', $kelasIdDiampu));
                        @endphp
                        <label class="flex items-center gap-2 text-sm cursor-pointer hover:text-green-700">
                            <input type="checkbox"
                                   name="kelas_ids[]"
                                   value="{{ $k->id }}"
                                   {{ $checked ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-green-700 focus:ring-green-600">
                            {{ $k->nama }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Password Baru</label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg hover:bg-green-800">
                Update
            </button>
            <a href="{{ route('users.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
    function toggleKelasSection() {
        const role    = document.getElementById('role-select').value;
        const section = document.getElementById('kelas-section');
        section.classList.toggle('hidden', role !== 'pengurus');
    }
</script>
@endsection
