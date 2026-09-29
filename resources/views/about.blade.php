@extends('layouts.store')
@section('title', 'Tentang Kami')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-12">
        <div class="text-center mb-10">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-600 text-2xl mb-4"><i class="fa-solid fa-book-open"></i></span>
            <h1 class="text-3xl font-extrabold text-gray-900">Tentang BookStore</h1>
            <p class="text-gray-500 text-sm mt-2">Jendela Ilmu dan Inspirasi untuk Generasi Cerdas</p>
        </div>
        <div class="space-y-6 text-gray-600 text-sm leading-relaxed">
            <p><strong>BookStore</strong> adalah platform toko buku online yang mempermudah pencinta buku mendapatkan buku berkualitas secara aman dan nyaman.</p>
            <p>Kami menyediakan beberapa metode pembayaran simulasi untuk kebutuhan demo. Tidak ada gateway, pemotongan saldo, atau transaksi nyata.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                <div class="p-4 bg-slate-50 rounded-lg">
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center"><i class="fa-solid fa-bullseye text-amber-500 mr-2"></i>Misi Kami</h3>
                    <p class="text-xs text-gray-500">Menyediakan katalog buku terlengkap dengan proses pemesanan yang sederhana dan transparan.</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-lg">
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center"><i class="fa-solid fa-handshake text-blue-500 mr-2"></i>Komitmen Kami</h3>
                    <p class="text-xs text-gray-500">Memberikan pelayanan terbaik dan kemudahan komunikasi langsung antara pembaca dan administrator.</p>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-bold shadow transition">
                    <i class="fa-solid fa-envelope mr-2"></i>Hubungi Admin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
