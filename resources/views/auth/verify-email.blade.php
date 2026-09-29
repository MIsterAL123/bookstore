<x-guest-layout>
    <x-slot name="title">Verifikasi Email</x-slot>

    <div class="mb-7">
        <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 text-2xl mb-4">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </span>
        <h2 class="text-2xl font-extrabold text-slate-900">Verifikasi Email Anda</h2>
        <p class="text-sm text-slate-500 mt-1">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi email Anda dengan mengklik tautan yang kami kirimkan. Bila belum menerima email, kami siap mengirim ulang.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 flex items-start">
            <i class="fa-solid fa-circle-check mt-0.5 mr-2"></i>
            <span>Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.</span>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-amber-500/30 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-red-500 transition">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
