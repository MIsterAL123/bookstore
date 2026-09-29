@extends('layouts.store')
@section('title', $book->title)
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 mb-6 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-amber-600">Beranda</a><span>/</span>
        <a href="{{ route('home', ['category' => $book->category_id]) }}" class="hover:text-amber-600">{{ $book->category->name }}</a><span>/</span>
        <span class="text-gray-900 font-semibold">{{ $book->title }}</span>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6 md:p-8">
            <div class="flex justify-center items-start">
                <img src="{{ $book->image_url }}" alt="{{ $book->title }}"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400';"
                     class="w-full max-w-xs rounded-xl shadow-lg object-cover">
            </div>
            <div class="md:col-span-2 flex flex-col justify-between">
                <div>
                    <span class="inline-block bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $book->category->name }}</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $book->title }}</h1>
                    <p class="text-sm text-gray-600 mb-4">Penulis: <span class="font-semibold text-gray-800">{{ $book->author }}</span></p>
                    <div class="text-2xl font-black text-amber-600 mb-6">Rp {{ number_format($book->price, 0, ',', '.') }}</div>
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-2">Deskripsi Buku</h3>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $book->description ?: 'Belum ada deskripsi untuk buku ini.' }}</p>
                    </div>
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="text-xs font-semibold text-gray-500">Ketersediaan:</span>
                        @if ($book->stock > 0)
                            <span class="text-xs font-bold text-green-700 bg-green-50 px-2.5 py-1 rounded"><i class="fa-solid fa-check-circle mr-1"></i>Tersedia ({{ $book->stock }} eksemplar)</span>
                        @else
                            <span class="text-xs font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded"><i class="fa-solid fa-xmark-circle mr-1"></i>Stok Habis</span>
                        @endif
                    </div>
                </div>
                <div class="pt-6 border-t border-gray-100">
                    @if ($book->stock > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <label for="quantity" class="px-3 text-xs text-gray-500 font-medium">Jumlah:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $book->stock }}" class="w-16 p-2 text-center text-sm font-bold focus:outline-none">
                            </div>
                            <button type="submit" class="w-full sm:w-auto flex-1 flex items-center justify-center space-x-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow transition">
                                <i class="fa-solid fa-cart-shopping"></i><span>Tambah ke Keranjang</span>
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full py-3 bg-gray-200 text-gray-400 rounded-lg font-semibold text-sm cursor-not-allowed">Buku ini sedang habis</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
