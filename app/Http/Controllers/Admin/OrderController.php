<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Admin OrderController mengelola tampilan dan status pesanan dari seluruh pengguna.
 */
class OrderController extends Controller
{
    /**
     * Tampilkan semua pesanan masuk lengkap dengan detail user dan item buku.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items.book'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update status pesanan (pending, processing, completed, cancelled).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui menjadi ' . $request->status . '.');
    }
}
