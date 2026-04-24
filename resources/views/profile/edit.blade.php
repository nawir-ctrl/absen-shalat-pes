<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Ubah Password
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Pastikan password akun menggunakan kombinasi yang aman.
        </p>
    </header>

    <form method="post" action="{{ route('profile.password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="current_password">Password Lama</label>
            <input id="current_password" name="current_password" type="password" class="mt-1 block w-full">
            @error('current_password', 'updatePassword')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password Baru</label>
            <input id="password" name="password" type="password" class="mt-1 block w-full">
            @error('password', 'updatePassword')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit">
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600">Password berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>