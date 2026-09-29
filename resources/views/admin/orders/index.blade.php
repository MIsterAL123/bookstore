@extends('layouts.admin')
@section('title', 'Daftar Pesanan')
@section('header', 'Daftar Pesanan Pelanggan')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800">Semua Pesanan Masuk</h2>
        <p class="text-sm text-gray-500">Monitoring & update status pemesanan buku pelanggan secara real-time</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <th class="p-4">ID Order</th><th class="p-4">Pelanggan</th><th class="p-4">Item Buku yang Dipesan</th><th class="p-4">Alamat Kirim</th><th class="p-4">Total</th><th class="p-4">Metode Bayar</th><th class="p-4">Status & Update</th><th class="p-4">Tanggal Order</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-900">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $order->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</div>
                        </td>
                        <td class="p-4">
                            <ul class="space-y-1 text-xs">
                                @foreach ($order->items as $item)
                                    <li class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-800">{{ $item->book->title ?? 'Buku' }}</span>
                                        <span class="text-gray-400">({{ $item->quantity }}x @ Rp {{ number_format($item->unit_price ?? $item->book->price ?? 0, 0, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-4">
                            @if ($order->shipping_address)
                                <div class="text-xs max-w-[220px]">
                                    <p class="font-semibold text-gray-800">{{ $order->recipient_name }}</p>
                                    <p class="text-gray-500 leading-snug">{{ $order->shipping_address }}</p>
                                    <p class="text-gray-500">{{ $order->city }}{{ $order->postal_code ? ' ' . $order->postal_code : '' }}</p>
                                    @if ($order->recipient_phone)
                                        <p class="text-gray-400 mt-0.5"><i class="fa-solid fa-phone mr-1"></i>{{ $order->recipient_phone }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum ada alamat</span>
                            @endif
                        </td>
                        <td class="p-4 font-extrabold text-amber-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="p-4"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700">{{ $order->payment_method_label }}</span></td>
                        <td class="p-4">
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center space-x-2">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-md border-gray-300 py-1 px-2 border focus:ring-amber-500 focus:outline-none">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </form>
                        </td>
                        <td class="p-4 text-gray-500 text-xs">{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center text-gray-500">
                            <i class="fa-solid fa-clipboard-list text-3xl mb-2 text-gray-300"></i>
                            <p class="font-medium">Belum ada pesanan masuk.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($orders->hasPages()) <div class="p-4 border-t border-gray-100">{{ $orders->links() }}</div> @endif
</div>
@endsection
