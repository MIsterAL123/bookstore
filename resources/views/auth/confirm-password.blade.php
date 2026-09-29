<x-guest-layout>
    <x-slot name="title">Konfirmasi Sandi</x-slot>

    <div class="mb-7">
        <h2 class="text-2xl font-extrabold text-slate-900">Konfirmasi Kata Sandi</h2>
        <p class="text-sm text-slate-500 mt-1">Ini area aman. Masukkan kata sandi Anda untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
            <div class="relative">
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

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-amber-500/30 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="fa-solid fa-shield-halved mr-2"></i> Konfirmasi
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
