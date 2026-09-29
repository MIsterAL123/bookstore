<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore') - Toko Buku Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-slate-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 font-extrabold text-xl text-amber-400">
                    <i class="fa-solid fa-book-open"></i><span>BookStore</span>
                </a>
                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-slate-300 hover:text-white transition {{ request()->routeIs('home') ? 'text-amber-400' : '' }}">Beranda</a>
                    <a href="{{ route('about') }}" class="text-slate-300 hover:text-white transition {{ request()->routeIs('about') ? 'text-amber-400' : '' }}">Tentang Kami</a>
                    @auth
                        <a href="{{ route('contact') }}" class="text-slate-300 hover:text-white transition {{ request()->routeIs('contact') ? 'text-amber-400' : '' }}">Kontak Admin</a>
                    @endauth
                </div>
                <!-- Right Side -->
                <div class="flex items-center space-x-4 text-sm">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition">
                                <i class="fa-solid fa-shield-halved"></i><span>Panel Admin</span>
                            </a>
                        @else
                            <a href="{{ route('cart.index') }}" class="relative flex items-center space-x-1 text-slate-300 hover:text-amber-400 transition px-3 py-1">
                                <i class="fa-solid fa-shopping-cart text-base"></i>
                                @php $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0; @endphp
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">{{ $cartCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('orders.my-orders') }}" class="text-slate-300 hover:text-white transition text-xs font-medium"><i class="fa-solid fa-box mr-1"></i>Pesanan</a>
                        @endif
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400 text-xs">Halo, <span class="text-amber-400 font-bold">{{ auth()->user()->name }}</span></span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition"><i class="fa-solid fa-right-from-bracket"></i></button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-1.5 text-slate-300 hover:text-white text-xs font-medium transition border border-slate-600 rounded-lg hover:border-amber-500">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition shadow">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        @if (session('success'))
            <div class="mt-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center text-sm"><i class="fa-solid fa-check-circle mr-2"></i><span>{{ session('success') }}</span></div>
        @endif
        @if (session('error'))
            <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center text-sm"><i class="fa-solid fa-triangle-exclamation mr-2"></i><span>{{ session('error') }}</span></div>
        @endif
        @if (session('warning'))
            <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 rounded shadow-sm flex items-center text-sm"><i class="fa-solid fa-exclamation mr-2"></i><span>{{ session('warning') }}</span></div>
        @endif
        @if ($errors->any())
            <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm">
                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">@yield('content')</main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-amber-400 text-lg font-extrabold mb-2"><i class="fa-solid fa-book-open mr-2"></i>BookStore</div>
            <p class="text-xs mb-4">Platform toko buku online dengan sistem pembayaran Cash on Delivery (COD).</p>
            <div class="flex justify-center space-x-6 text-xs">
                <a href="{{ route('home') }}" class="hover:text-white transition">Katalog Buku</a>
                <a href="{{ route('about') }}" class="hover:text-white transition">Tentang Kami</a>
                @auth <a href="{{ route('contact') }}" class="hover:text-white transition">Hubungi Admin</a> @endauth
            </div>
            <p class="text-xs text-slate-600 mt-4">&copy; {{ date('Y') }} BookStore. Semua hak dilindungi.</p>
        </div>
    </footer>
    <x-confirm-dialog />

</body>
</html>
