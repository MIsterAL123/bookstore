<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - BookStore Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-slate-800 text-white flex flex-col flex-shrink-0">
            <div class="p-5 text-xl font-bold border-b border-slate-700 flex items-center space-x-2">
                <i class="fa-solid fa-book-open text-amber-400"></i>
                <span>BookStore Admin</span>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-6"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-6"></i><span>Kategori</span>
                </a>
                <a href="{{ route('admin.books.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.books.*') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-book w-6"></i><span>Buku</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-users w-6"></i><span>User</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-shopping-cart w-6"></i><span>Pesanan</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.messages.*') ? 'bg-amber-500 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-envelope w-6"></i><span>Pesan Masuk</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700">
                <div class="text-xs text-slate-400 mb-1">Login sebagai:</div>
                <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-amber-400 mb-3">{{ auth()->user()->email }} (Admin)</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded bg-slate-700 text-red-300 hover:bg-red-600 hover:text-white text-xs transition">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <header class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">@yield('header')</h1>
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center">
                    <i class="fa-solid fa-external-link-alt mr-1"></i> Buka Toko (User View)
                </a>
            </header>
            <main class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center"><i class="fa-solid fa-check-circle mr-2"></i><span>{{ session('success') }}</span></div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center"><i class="fa-solid fa-triangle-exclamation mr-2"></i><span>{{ session('error') }}</span></div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <x-confirm-dialog />

</body>
</html>
