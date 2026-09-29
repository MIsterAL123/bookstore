<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Masuk' }} &middot; BookStore</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN (pengganti Vite untuk kesederhanaan) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col lg:flex-row">

            {{-- ============ PANEL BRANDING (kiri) ============ --}}
            <aside class="relative hidden lg:flex lg:w-1/2 xl:w-[55%] flex-col justify-between overflow-hidden bg-slate-900 text-white p-10 xl:p-14">
                {{-- Latar dekoratif: gradient + glow amber --}}
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-amber-950"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-amber-500/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-amber-600/10 blur-3xl"></div>
                {{-- Pola titik halus --}}
                <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 26px 26px;"></div>

                {{-- Brand --}}
                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 group">
                        <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-amber-500 text-slate-900 shadow-lg shadow-amber-500/30 group-hover:scale-105 transition">
                            <i class="fa-solid fa-book-open text-xl"></i>
                        </span>
                        <span class="text-2xl font-extrabold tracking-tight">Book<span class="text-amber-400">Store</span></span>
                    </a>
                </div>

                {{-- Pesan utama --}}
                <div class="relative z-10 max-w-md">
                    <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight mb-4">
                        Jendela Ilmu &amp; Inspirasi untuk <span class="text-amber-400">Generasi Cerdas</span>
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed mb-8">
                        Temukan judul buku berkualitas, pesan dengan praktis, lalu gunakan pilihan pembayaran simulasi saat checkout.
                    </p>

                    {{-- Poin keunggulan --}}
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 text-amber-400 shrink-0"><i class="fa-solid fa-truck-fast"></i></span>
                            <div>
                                <p class="text-sm font-semibold">Pembayaran Simulasi</p>
                                <p class="text-xs text-slate-400">Tidak ada uang yang dikirim atau dipotong dari saldo.</p>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 text-amber-400 shrink-0"><i class="fa-solid fa-book-bookmark"></i></span>
                            <div>
                                <p class="text-sm font-semibold">Katalog Lengkap</p>
                                <p class="text-xs text-slate-400">Fiksi, teknologi, sejarah, bisnis, dan banyak lagi.</p>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 text-amber-400 shrink-0"><i class="fa-solid fa-headset"></i></span>
                            <div>
                                <p class="text-sm font-semibold">Terhubung dengan Admin</p>
                                <p class="text-xs text-slate-400">Kirim pertanyaan langsung lewat menu kontak.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Footer panel --}}
                <p class="relative z-10 text-xs text-slate-500">
                    &copy; {{ date('Y') }} BookStore. Semua hak dilindungi.
                </p>
            </aside>

            {{-- ============ PANEL FORM (kanan) ============ --}}
            <main class="flex-1 flex flex-col justify-center items-center bg-slate-50 px-5 py-10 sm:px-8">
                {{-- Brand untuk mobile (panel kiri tersembunyi) --}}
                <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center space-x-2 mb-8">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-500 text-slate-900 shadow"><i class="fa-solid fa-book-open"></i></span>
                    <span class="text-xl font-extrabold text-slate-900">Book<span class="text-amber-500">Store</span></span>
                </a>

                <div class="w-full max-w-md">
                    {{-- Kartu form --}}
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-7 sm:p-9">
                        {{ $slot }}
                    </div>

                    {{-- Tautan kembali ke toko --}}
                    <p class="text-center text-xs text-slate-400 mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center hover:text-amber-600 transition">
                            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Beranda
                        </a>
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
