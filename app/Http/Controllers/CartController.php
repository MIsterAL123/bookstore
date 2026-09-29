<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CartController mengelola keranjang belanja user yang sudah login.
 */
class CartController extends Controller
{
    /**
     * Pastikan semua method di controller ini memerlukan autentikasi.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan isi keranjang belanja user saat ini.
     * Sekaligus mengambil alamat pengiriman terakhir user untuk mengisi form otomatis.
     */
    public function index()
    {
        $cartItems = Cart::with(['book.category'])
            ->where('user_id', Auth::id())
            ->get();

        $totalPrice = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        // Ambil pesanan terakhir sebagai acuan prefill alamat pengiriman
        $lastOrder = \App\Models\Order::where('user_id', Auth::id())
            ->whereNotNull('shipping_address')
            ->latest()
            ->first();

        return view('cart.index', compact('cartItems', 'totalPrice', 'lastOrder'));
    }

    /**
     * Tambahkan buku ke keranjang. Jika sudah ada, tambahkan kuantitasnya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id'  => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Cek stok tersedia
        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi. Tersisa ' . $book->stock . ' eksemplar.');
        }

        // Jika buku sudah ada di keranjang, tambahkan kuantitas
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $book->stock) {
                return back()->with('error', 'Total kuantitas melebihi stok tersedia.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id'  => Auth::id(),
                'book_id'  => $request->book_id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', '"' . $book->title . '" berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update kuantitas item di keranjang.
     */
    public function update(Request $request, Cart $cart)
    {
        // Pastikan cart milik user yang login
        abort_if($cart->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($request->quantity > $cart->book->stock) {
            return back()->with('error', 'Kuantitas melebihi stok tersedia (' . $cart->book->stock . ').');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Kuantitas berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function destroy(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
