<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Update status pesanan dengan transisi dan stok yang konsisten.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            DB::transaction(function () use ($order, $validated): void {
                $lockedOrder = Order::with('items')
                    ->whereKey($order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $transitions = [
                    'pending' => ['processing', 'cancelled'],
                    'processing' => ['completed', 'cancelled'],
                    'completed' => [],
                    'cancelled' => [],
                ];
                $nextStatus = $validated['status'];

                if (!in_array($nextStatus, $transitions[$lockedOrder->status], true)) {
                    throw new \DomainException(
                        'Status pesanan tidak dapat diubah dari ' . $lockedOrder->status . ' menjadi ' . $nextStatus . '.'
                    );
                }

                if ($nextStatus === 'cancelled') {
                    $books = Book::whereIn('id', $lockedOrder->items->pluck('book_id'))
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($lockedOrder->items as $item) {
                        $book = $books->get($item->book_id);
                        if (!$book) {
                            throw new \DomainException('Buku pada pesanan tidak ditemukan.');
                        }

                        $book->increment('stock', $item->quantity);
                    }
                }

                $lockedOrder->update(['status' => $nextStatus]);
            });
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui menjadi ' . $validated['status'] . '.');
    }
}
