<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * HomeController mengelola halaman publik: katalog buku, detail buku, dan halaman about.
 */
class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama dengan katalog buku.
     * Mendukung pencarian berdasarkan judul/penulis dan filter kategori.
     */
    public function index(Request $request)
    {
        $query = Book::with('category')->where('stock', '>', 0);

        // Filter pencarian judul atau penulis
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('author', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount('books')->get();

        return view('home', compact('books', 'categories'));
    }

    /**
     * Tampilkan halaman detail buku berdasarkan ID.
     */
    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);
        return view('books.show', compact('book'));
    }

    /**
     * Tampilkan halaman About Us statis.
     */
    public function about()
    {
        return view('about');
    }
}
