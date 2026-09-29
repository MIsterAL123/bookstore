<x-guest-layout>
    <x-slot name="title">Reset Sandi</x-slot>

    <div class="mb-7">
        <h2 class="text-2xl font-extrabold text-slate-900">Atur Ulang Kata Sandi</h2>
        <p class="text-sm text-slate-500 mt-1">Buat kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-400 @else border-slate-300 @enderror">
            </div>
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="w-full pl-10 pr-11 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-400 @else border-slate-300 @enderror">
                <button type="button" onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition" aria-label="Tampilkan sandi">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Ulangi kata sandi"
                       class="w-full pl-10 pr-11 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('password_confirmation') border-red-400 @else border-slate-300 @enderror">
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition" aria-label="Tampilkan sandi">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-amber-500/30 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="fa-solid fa-key mr-2"></i> Simpan Kata Sandi Baru
        </button>
    </form>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</x-guest-layout>
