<x-guest-layout>
    <x-slot name="title">Lupa Sandi</x-slot>

    <div class="mb-7">
        <h2 class="text-2xl font-extrabold text-slate-900">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 mt-1">Masukkan email Anda, kami akan mengirimkan tautan untuk mengatur ulang sandi.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 flex items-start">
            <i class="fa-solid fa-circle-check mt-0.5 mr-2"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-400 @else border-slate-300 @enderror">
            </div>
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full flex items-center justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-amber-500/30 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Tautan Reset
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-7">
        Ingat sandi Anda?
        <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-700 transition">Kembali ke Masuk</a>
    </p>
</x-guest-layout>
