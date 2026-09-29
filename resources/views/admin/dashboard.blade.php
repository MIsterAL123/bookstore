@extends('layouts.admin')
@section('title', 'Dashboard')
@section('header', 'Dashboard Utama')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Kategori</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalCategories }}</p>
        </div>
        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-tags"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Judul Buku</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalBooks }}</p>
        </div>
        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-book"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Pelanggan</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Total Pesanan</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalOrders }}</p>
        </div>
        <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-shopping-cart"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Pending</p>
            <p class="text-2xl font-extrabold text-yellow-600 mt-1">{{ $pendingOrders }}</p>
        </div>
        <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-clock"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500">Pesan Masuk</p>
            <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $totalMessages }}</p>
        </div>
        <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center text-lg">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-800">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 uppercase">
                        <th class="p-2.5">ID</th>
                        <th class="p-2.5">Pelanggan</th>
                        <th class="p-2.5">Total</th>
                        <th class="p-2.5">Status</th>
                        <th class="p-2.5">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2.5 font-bold">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-2.5">{{ $order->user->name ?? '-' }}</td>
                            <td class="p-2.5 font-bold text-amber-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="p-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    @if($order->status === 'completed') bg-green-100 text-green-700
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-700
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">{{ $order->status }}</span>
                            </td>
                            <td class="p-2.5 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-base font-bold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="space-y-2">
            <a href="{{ route('admin.categories.create') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-amber-500 hover:bg-amber-50 text-xs font-semibold text-gray-700 transition">
                <span><i class="fa-solid fa-tags text-amber-500 mr-2"></i>Tambah Kategori</span>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.books.create') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 text-xs font-semibold text-gray-700 transition">
                <span><i class="fa-solid fa-book text-blue-500 mr-2"></i>Tambah Buku Baru</span>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-rose-500 hover:bg-rose-50 text-xs font-semibold text-gray-700 transition">
                <span><i class="fa-solid fa-envelope text-rose-500 mr-2"></i>Pesan Masuk ({{ $totalMessages }})</span>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-slate-700 hover:bg-slate-50 text-xs font-semibold text-gray-700 transition">
                <span><i class="fa-solid fa-store text-slate-700 mr-2"></i>Buka Tampilan Toko</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-gray-400"></i>
            </a>
        </div>
        <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-500">
            Total Omzet (Selesai): <span class="font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
