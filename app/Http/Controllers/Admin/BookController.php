<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar semua buku dalam katalog
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Menampilkan form pembuatan buku baru
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Menyimpan data buku baru ke database.
     * Mendukung upload file cover (PNG, SVG, JPG, dll) atau input URL gambar.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            $data['image_url'] = $request->file('image_file')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $this->cleanImageUrl($request->image_url);
        }

        unset($data['image_file']);

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit buku
     */
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Memperbarui data buku di database.
     * Mendukung upload file gambar baru, URL gambar baru, atau mempertahankan gambar lama.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            // Hapus file lama jika ada dan tersimpan di storage lokal
            $oldImage = $book->getRawOriginal('image_url');
            if ($oldImage && !str_starts_with($oldImage, 'http://') && !str_starts_with($oldImage, 'https://')) {
                Storage::disk('public')->delete($oldImage);
            }
            $data['image_url'] = $request->file('image_file')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $this->cleanImageUrl($request->image_url);
        } else {
            unset($data['image_url']);
        }

        unset($data['image_file']);

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Menghapus data buku beserta file gambar cover jika tersimpan di lokal.
     */
    public function destroy(Book $book)
    {
        $oldImage = $book->getRawOriginal('image_url');
        if ($oldImage && !str_starts_with($oldImage, 'http://') && !str_starts_with($oldImage, 'https://')) {
            Storage::disk('public')->delete($oldImage);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Helper untuk membersihkan URL gambar.
     * Jika URL berasal dari Google Images (google.com/imgres?imgurl=...),
     * otomatis ekstrak direct link gambar aslinya.
     */
    protected function cleanImageUrl(?string $url): ?string
    {
        if (!$url) return $url;

        if (str_contains($url, 'google.') && str_contains($url, 'imgurl=')) {
            $parsed = parse_url($url);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $params);
                if (!empty($params['imgurl'])) {
                    return urldecode($params['imgurl']);
                }
            }
        }

        return $url;
    }
}
