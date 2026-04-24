@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold">Kelola User</h3>
            <p class="text-sm text-gray-500">Atur akun admin, musyrif, dan pengurus.</p>
        </div>

        <a href="{{ route('users.create') }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
            + Tambah User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="keyword" value="{{ $keyword ?? '' }}"
                       placeholder="Cari nama, email, atau role..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
            </div>

            <div class="flex gap-2">
                <select name="per_page" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    <option value="10"  {{ ($perPage ?? '10') == '10'  ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ ($perPage ?? '') == '25'    ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ ($perPage ?? '') == '50'    ? 'selected' : '' }}>50</option>
                    <option value="all" {{ ($perPage ?? '') === 'all'  ? 'selected' : '' }}>All</option>
                </select>

                <button type="submit"
                        class="bg-gray-700 text-white px-4 py-2.5 rounded-lg hover:bg-gray-800">
                    Cari
                </button>
            </div>
        </form>
    </div>

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

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Kelas Diampu</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $users->firstItem() + $loop->index }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @if($user->role === 'admin')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        Admin
                                    </span>
                                @elseif($user->role === 'musyrif')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                        Musyrif
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">
                                        Pengurus
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->role === 'pengurus')
                                    @if($user->kelasDiampu->isEmpty())
                                        <span class="text-gray-400 text-xs italic">Belum ada kelas</span>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->kelasDiampu as $kelas)
                                                <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs">
                                                    {{ $kelas->nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-4 py-4 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
