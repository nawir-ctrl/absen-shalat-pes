<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $perPage = $request->per_page ?? '10';

        $query = User::with('kelasDiampu')->latest();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%')
                  ->orWhere('role', 'like', '%' . $keyword . '%');
            });
        }

        if ($perPage === 'all') {
            $users = $query->get();
        } else {
            $users = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('users.index', compact('users', 'keyword', 'perPage'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();

        return view('users.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => 'required|in:admin,musyrif,pengurus',
            'password' => 'required|string|min:8|confirmed',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign kelas hanya untuk pengurus
        if ($validated['role'] === 'pengurus' && !empty($validated['kelas_ids'])) {
            $user->kelasDiampu()->sync($validated['kelas_ids']);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $kelas = Kelas::orderBy('nama')->get();
        $kelasIdDiampu = $user->kelasDiampu->pluck('id')->toArray();

        return view('users.edit', compact('user', 'kelas', 'kelasIdDiampu'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role'     => 'required|in:admin,musyrif,pengurus',
            'password' => 'nullable|string|min:8|confirmed',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Sync kelas: pengurus dapat assign, role lain kosongkan
        if ($validated['role'] === 'pengurus') {
            $user->kelasDiampu()->sync($validated['kelas_ids'] ?? []);
        } else {
            $user->kelasDiampu()->detach();
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
