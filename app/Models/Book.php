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
     * Otomatis mengenali apakah disimpan sebagai URL eksternal (http/https)
     * atau path file yang di-upload ke storage lokal (storage/books/...).
     * Juga otomatis mengekstrak direct URL jika user menempelkan link Google Images.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400';
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

        return asset('storage/' . $value);
    }
}
