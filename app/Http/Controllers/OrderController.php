<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * OrderController mengelola proses checkout dan riwayat pesanan user.
 */
class OrderController extends Controller
{
    /**
     * Semua method memerlukan autentikasi.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Proses checkout dari keranjang belanja.
     * Buat record di tabel orders dan order_items, lalu kosongkan cart user.
     * Data alamat pengiriman divalidasi dan disimpan bersama pesanan.
     */
    public function checkout(Request $request)
    {
        // Validasi data pengiriman sebelum memproses pesanan
        $validated = $request->validate([
            'recipient_name'   => 'required|string|max:100',
            'recipient_phone'  => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'city'             => 'required|string|max:100',
            'postal_code'      => 'required|string|max:10',
        ], [
            'recipient_name.required'   => 'Nama penerima wajib diisi.',
            'recipient_phone.required'  => 'Nomor telepon wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'city.required'             => 'Kota/kabupaten wajib diisi.',
            'postal_code.required'      => 'Kode pos wajib diisi.',
        ]);

        $cartItems = Cart::with('book')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi ulang ketersediaan stok saat checkout.
        // Stok bisa berkurang setelah item dimasukkan ke keranjang, sehingga tanpa
        // pengecekan ini stok dapat menjadi negatif (oversell).
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->book->stock) {
                return redirect()->route('cart.index')->with(
                    'error',
                    'Stok "' . $item->book->title . '" tidak mencukupi. Tersisa ' . $item->book->stock . ' eksemplar, silakan sesuaikan jumlah di keranjang.'
                );
            }
        }

        // Hitung total harga
        $totalPrice = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        DB::transaction(function () use ($cartItems, $totalPrice, $validated) {
            // Buat record order baru dengan metode Payment at Delivery + alamat kirim
            $order = Order::create([
                'user_id'          => Auth::id(),
                'recipient_name'   => $validated['recipient_name'],
                'recipient_phone'  => $validated['recipient_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city'             => $validated['city'],
                'postal_code'      => $validated['postal_code'],
                'payment_method'   => 'Payment at Delivery',
                'status'           => 'pending',
                'total_price'      => $totalPrice,
            ]);

            // Buat order items dan kurangi stok buku
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'book_id'   => $item->book_id,
                    'quantity'  => $item->quantity,
                    'subtotal'  => $item->book->price * $item->quantity,
                ]);

                // Kurangi stok buku
                $item->book->decrement('stock', $item->quantity);
            }

            // Kosongkan cart user setelah checkout berhasil
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders.my-orders')
            ->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses. Bayar saat buku tiba (COD).');
    }

    /**
     * Tampilkan riwayat pesanan milik user yang sedang login.
     */
    public function myOrders()
    {
        $orders = Order::with(['items.book'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }
}
