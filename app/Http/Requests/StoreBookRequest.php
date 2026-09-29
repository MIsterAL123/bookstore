<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi penambahan buku baru
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg,gif|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category_id.required' => 'Pilih kategori buku.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'image_url.url' => 'Format URL gambar tidak valid.',
            'image_file.mimes' => 'Format file gambar harus PNG, JPG, JPEG, WEBP, SVG, atau GIF.',
            'image_file.max' => 'Ukuran file gambar maksimal 5 MB.',
        ];
    }
}
