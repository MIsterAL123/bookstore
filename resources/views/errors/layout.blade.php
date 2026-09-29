{{--
    Kerangka halaman error bertheme BookStore.
    Sengaja TIDAK memakai @auth / query database: halaman error harus tetap
    bisa dirender walau sesi atau database sedang bermasalah.

    Variabel yang dipakai:
        $code    -> kode status HTTP
        $heading -> judul besar
        $icon    -> kelas ikon Font Awesome
        $message -> kalimat penjelasan
        $reasons -> (opsional) array alasan, ditampilkan sebagai daftar
        $hint    -> (opsional) catatan kecil di bawah daftar
        $showLogin -> (opsional) tampilkan tombol "Masuk Lagi"
--}}
@php
    $code = $code ?? 500;
    $heading = $heading ?? 'Terjadi Kesalahan';
    $icon = $icon ?? 'fa-triangle-exclamation';
    $message = $message ?? 'Permintaan Anda tidak dapat diproses saat ini.';
    $reasons = $reasons ?? [];
    $hint = $hint ?? null;
    $showLogin = $showLogin ?? false;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} &middot; BookStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-slate-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 font-extrabold text-xl text-amber-400">
                    <i class="fa-solid fa-book-open"></i><span>BookStore</span>
                </a>
                <a href="{{ url('/') }}" class="text-slate-300 hover:text-white text-xs font-medium transition">
                    <i class="fa-solid fa-house mr-1"></i>Beranda
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-lg text-center">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-amber-50 text-amber-600 ring-8 ring-amber-50/70 mb-6">
                <i class="fa-solid {{ $icon }} text-3xl"></i>
            </div>

            <p class="text-amber-600 font-bold text-sm tracking-widest uppercase mb-2">{{ $code }} Error</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">{{ $heading }}</h1>
            <p class="text-slate-500 leading-relaxed mb-8">{{ $message }}</p>

            @if (!empty($reasons) || $hint)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-left mb-8">
                    @if (!empty($reasons))
                        <h2 class="text-sm font-bold text-slate-900 flex items-center mb-3">
                            <i class="fa-solid fa-circle-info text-amber-500 mr-2"></i>Kenapa ini terjadi?
                        </h2>
                        <ul class="text-sm text-slate-600 space-y-2 list-disc list-inside">
                            @foreach ($reasons as $reason)
                                <li>{!! $reason !!}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($hint)
                        <p class="text-xs text-slate-400 {{ !empty($reasons) ? 'mt-4' : '' }} flex items-start">
                            <i class="fa-solid fa-shield-halved text-slate-400 mr-1.5 mt-0.5"></i>
                            <span>{!! $hint !!}</span>
                        </p>
                    @endif
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow transition">
                    <i class="fa-solid fa-house"></i> Kembali ke Beranda
                </a>
                @if ($showLogin)
                    <a href="{{ url('/login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-bold text-sm shadow transition">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk Lagi
                    </a>
                @endif
            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-amber-400 font-extrabold mb-1"><i class="fa-solid fa-book-open mr-2"></i>BookStore</div>
            <p class="text-xs text-slate-600">&copy; {{ date('Y') }} BookStore. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
