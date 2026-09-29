<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    {{-- Judul --}}
    <div class="mb-7">
        <h2 class="text-2xl font-extrabold text-slate-900">Selamat Datang Kembali</h2>
        <p class="text-sm text-slate-500 mt-1">Masuk untuk melanjutkan belanja buku favoritmu.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 flex items-start">
            <i class="fa-solid fa-circle-check mt-0.5 mr-2"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-400 @else border-slate-300 @enderror">
            </div>
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-amber-600 hover:text-amber-700 transition">Lupa sandi?</a>
                @endif
            </div>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-10 pr-11 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-400 @else border-slate-300 @enderror">
                <button type="button" onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition" aria-label="Tampilkan sandi">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
            <input id="remember_me" type="checkbox" name="remember"
                   class="rounded border-slate-300 text-amber-500 shadow-sm focus:ring-amber-500">
            <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
        </label>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-amber-500/30 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Akun
        </button>
    </form>

    <!-- Register Link -->
    <p class="text-center text-sm text-slate-500 mt-7">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-amber-600 hover:text-amber-700 transition">Daftar sekarang</a>
    </p>

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
