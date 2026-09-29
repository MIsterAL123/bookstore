@extends('layouts.store')
@section('title', 'Pesanan Saya')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center"><i class="fa-solid fa-box text-amber-500 mr-3"></i>Riwayat Pesanan Saya</h1>
            <p class="text-xs text-gray-500">Daftar buku yang telah dipesan dengan metode Payment at Delivery</p>
        </div>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold">Beli Buku Lagi</a>
    </div>
    @forelse ($orders as $order)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-50 p-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2 text-xs">
                <div>
                    <span class="font-bold text-gray-900 text-sm">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-gray-400 mx-2">|</span>
                    <span class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-2.5 py-1 rounded-full font-bold uppercase tracking-wider text-[10px]
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">{{ $order->status }}</span>
                    <span class="bg-amber-100 text-amber-800 font-semibold px-2 py-1 rounded">{{ $order->payment_method }}</span>
                </div>
            </div>
            <div class="divide-y divide-gray-100 p-4">
                @foreach ($order->items as $item)
                    <div class="py-3 flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $item->book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400' }}" alt="{{ $item->book->title }}" class="w-10 h-14 object-cover rounded shadow-sm">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $item->book->title }}</h4>
                                <div class="text-xs text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->book->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Alamat pengiriman pesanan ini --}}
            @if ($order->shipping_address)
                <div class="bg-amber-50/60 p-4 border-t border-amber-100 text-xs">
                    <div class="flex items-start space-x-2 text-amber-900">
                        <i class="fa-solid fa-location-dot text-amber-600 mt-0.5"></i>
                        <div>
                            <p class="font-bold mb-0.5">Dikirim ke: {{ $order->recipient_name }}</p>
                            <p class="text-amber-800">{{ $order->shipping_address }}</p>
                            <p class="text-amber-800">
                                {{ $order->city }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}
                                @if ($order->recipient_phone) &middot; Telp: {{ $order->recipient_phone }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center text-sm">
                <span class="font-medium text-gray-600">Total Pembayaran:</span>
                <span class="font-extrabold text-amber-600 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <i class="fa-solid fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
            <h3 class="font-bold text-gray-800 mb-1">Belum Ada Riwayat Pesanan</h3>
            <a href="{{ route('home') }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-lg text-xs font-bold inline-block mt-4">Mulai Belanja</a>
        </div>
    @endforelse
    @if ($orders->hasPages()) <div class="mt-6">{{ $orders->links() }}</div> @endif
</div>
@endsection
