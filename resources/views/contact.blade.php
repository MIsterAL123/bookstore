@extends('layouts.store')
@section('title', 'Hubungi Admin')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-10">
        <div class="text-center mb-8">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 text-amber-600 text-xl mb-3"><i class="fa-solid fa-paper-plane"></i></span>
            <h1 class="text-2xl font-extrabold text-gray-900">Hubungi Administrator</h1>
            <p class="text-xs text-gray-500 mt-1">Kirimkan kritik, saran, pertanyaan atau permintaan buku kepada tim admin.</p>
        </div>
        @auth
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pengirim</label>
                    <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-800 font-medium border border-gray-200">
                        {{ auth()->user()->name }} ({{ auth()->user()->email }})
                    </div>
                </div>
                <div class="mb-6">
                    <label for="content" class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pesan Anda</label>
                    <textarea name="content" id="content" rows="5" required placeholder="Tuliskan pesan Anda di sini..." class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm @error('content') border-red-500 @else border-gray-300 @enderror">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i><span>Kirim Pesan ke Admin</span>
                </button>
            </form>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
                <i class="fa-solid fa-lock text-3xl text-amber-500 mb-2"></i>
                <h3 class="font-bold text-gray-800 text-base mb-1">Login Diperlukan</h3>
                <p class="text-xs text-gray-600 mb-4">Silakan masuk ke akun Anda terlebih dahulu untuk mengirimkan pesan ke admin.</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('login') }}" class="px-5 py-2 bg-amber-500 text-white rounded-lg text-xs font-bold">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold">Daftar Akun Baru</a>
                </div>
            </div>
        @endauth
    </div>
</div>
@endsection
