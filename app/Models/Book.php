<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'stock',
        'image_url',
        'category_id',
    ];

    /**
     * Relasi buku ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi buku ke order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor untuk mendapatkan URL lengkap gambar cover buku.
     * Otomatis mengenali URL eksternal, cover bawaan di public/images/books,
     * atau path file yang di-upload ke storage lokal.
     * Juga otomatis mengekstrak direct URL jika user menempelkan link Google Images.
     */
    public function getImageUrlAttribute($value)
    {
        $localCovers = [
            'Laskar Pelangi' => 'images/books/laskar-pelangi.svg',
            'Bumi Manusia' => 'images/books/bumi-manusia.svg',
            'Atomic Habits' => 'images/books/atomic-habits.svg',
            'Clean Code' => 'images/books/clean-code.svg',
            'Laravel Up & Running' => 'images/books/laravel-up-running.svg',
            'Sapiens' => 'images/books/sapiens.svg',
            'Rich Dad Poor Dad' => 'images/books/rich-dad-poor-dad.svg',
            'The Lean Startup' => 'images/books/the-lean-startup.svg',
        ];
        $localCover = $localCovers[$this->title] ?? 'images/books/default.svg';

        if (!$value || str_contains($value, 'images.unsplash.com')) {
            return asset($localCover);
        }

        // Jika user memasukkan link halaman pencarian Google Images (google.com/imgres?imgurl=...)
        if (str_contains($value, 'google.') && str_contains($value, 'imgurl=')) {
            $parsed = parse_url($value);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $params);
                if (!empty($params['imgurl'])) {
                    return urldecode($params['imgurl']);
                }
            }
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, 'images/')) {
            return asset($value);
        }

        return asset('storage/' . $value);
    }
}
