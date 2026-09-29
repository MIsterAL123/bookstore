<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'payment_method'   => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
        ], [
            'recipient_name.required'   => 'Nama penerima wajib diisi.',
            'recipient_phone.required'  => 'Nomor telepon wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'city.required'             => 'Kota/kabupaten wajib diisi.',
            'postal_code.required'      => 'Kode pos wajib diisi.',
            'payment_method.required'   => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'         => 'Metode pembayaran tidak tersedia.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $cartItems = Cart::where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \DomainException('Keranjang belanja Anda kosong.');
                }

                $books = Book::whereIn('id', $cartItems->pluck('book_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($cartItems as $item) {
                    $book = $books->get($item->book_id);
                    if (!$book || $item->quantity > $book->stock) {
                        $title = $book?->title ?? 'Buku yang dipilih';
                        $stock = $book?->stock ?? 0;
                        throw new \DomainException(
                            'Stok "' . $title . '" tidak mencukupi. Tersisa ' . $stock . ' eksemplar, silakan sesuaikan jumlah di keranjang.'
                        );
                    }
                }

                $totalPrice = $cartItems->sum(
                    fn ($item) => $books->get($item->book_id)->price * $item->quantity
                );

                // Simpan metode sebagai pilihan simulasi. Tidak ada gateway atau pembayaran nyata.
                $order = Order::create([
                    'user_id'          => Auth::id(),
                    'recipient_name'   => $validated['recipient_name'],
                    'recipient_phone'  => $validated['recipient_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'city'             => $validated['city'],
                    'postal_code'      => $validated['postal_code'],
                    'payment_method'   => $validated['payment_method'],
                    'status'           => 'pending',
                    'total_price'      => $totalPrice,
                ]);

                foreach ($cartItems as $item) {
                    $book = $books->get($item->book_id);
                    $updated = Book::whereKey($book->id)
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);

                    if ($updated !== 1) {
                        throw new \DomainException(
                            'Stok "' . $book->title . '" berubah saat checkout. Silakan coba lagi.'
                        );
                    }

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'book_id'    => $book->id,
                        'quantity'   => $item->quantity,
                        'unit_price' => $book->price,
                        'subtotal'   => $book->price * $item->quantity,
                    ]);
                }

                Cart::where('user_id', Auth::id())->delete();
            });
        } catch (\DomainException $exception) {
            return redirect()->route('cart.index')->with('error', $exception->getMessage());
        }

        return redirect()->route('orders.my-orders')
            ->with('success', 'Checkout berhasil! Metode ' . Order::paymentMethodLabel($validated['payment_method']) . ' tersimpan. Ini hanya simulasi, tidak ada pembayaran nyata.');
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
