<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;

/**
 * Admin DashboardController menampilkan ringkasan statistik keseluruhan aplikasi.
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik: total buku, kategori, user, pesanan, dan pesan.
     */
    public function index()
    {
        $totalBooks      = Book::count();
        $totalCategories = Category::count();
        $totalUsers      = User::where('role', 'user')->count();
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $totalMessages   = Message::count();
        $totalRevenue    = Order::where('status', 'completed')->sum('total_price');
        $recentOrders    = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalCategories', 'totalUsers',
            'totalOrders', 'pendingOrders', 'totalMessages',
            'totalRevenue', 'recentOrders'
        ));
    }
}
