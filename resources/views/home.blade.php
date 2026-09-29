@extends('layouts.store')
@section('title', 'Katalog Buku Lengkap')
@section('content')
<div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950 text-white py-12 px-4 shadow-inner">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-3 tracking-tight">Temukan Buku Favoritmu di <span class="text-amber-400">BookStore</span></h1>
        <p class="text-slate-300 max-w-xl mx-auto text-sm md:text-base mb-6">Pesan buku dengan praktis, lalu pilih metode pembayaran simulasi saat checkout.</p>
        <form action="{{ route('home') }}" method="GET" class="max-w-2xl mx-auto flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul buku atau penulis..." class="w-full pl-10 pr-4 py-3 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-sm text-sm">
            </div>
            <select name="category" class="py-3 px-4 rounded-lg text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->books_count }})</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow transition">Cari</button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">
                @if(request('q')) Hasil pencarian: "<span class="text-amber-600">{{ request('q') }}</span>" @else Katalog Buku Terbaru @endif
            </h2>
            <p class="text-xs text-gray-500">Menampilkan {{ $books->total() }} buku tersedia</p>
        </div>
        @if(request('q') || request('category'))
            <a href="{{ route('home') }}" class="text-xs text-red-500 hover:underline flex items-center"><i class="fa-solid fa-xmark mr-1"></i>Reset Filter</a>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($books as $book)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 flex flex-col overflow-hidden group">
                <a href="{{ route('books.show', $book->id) }}" class="relative block bg-gray-100 h-64 overflow-hidden">
                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/books/default.svg') }}';"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <span class="absolute top-2 right-2 bg-amber-500 text-white text-xs font-semibold px-2 py-1 rounded shadow">{{ $book->category->name }}</span>
                </a>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Penulis: {{ $book->author }}</div>
                        <h3 class="font-bold text-gray-900 text-base leading-snug line-clamp-2 mb-2">
                            <a href="{{ route('books.show', $book->id) }}" class="hover:text-amber-600 transition">{{ $book->title }}</a>
                        </h3>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="text-base font-extrabold text-amber-600">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                            <span class="text-xs {{ $book->stock > 0 ? 'text-green-600' : 'text-red-500' }}">Stok: {{ $book->stock }}</span>
                        </div>
                        @if ($book->stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2 bg-slate-900 hover:bg-amber-500 text-white rounded-lg text-xs font-semibold transition">
                                    <i class="fa-solid fa-cart-plus"></i><span>Tambah ke Keranjang</span>
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2 bg-gray-200 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed">Stok Habis</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-xl border border-gray-100 p-8">
                <i class="fa-solid fa-book-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-base font-semibold text-gray-700">Tidak ada buku yang ditemukan</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 px-4 py-2 bg-amber-500 text-white rounded-lg text-xs font-medium">Lihat Semua Buku</a>
            </div>
        @endforelse
    </div>
    @if ($books->hasPages()) <div class="mt-10">{{ $books->links() }}</div> @endif
</div>
@endsection
