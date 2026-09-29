<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi database dengan data awal: admin, kategori, dan buku dummy.
     * Idempoten — aman dijalankan berulang tanpa error duplicate entry.
     */
    public function run(): void
    {
        // Buat akun admin default
        User::updateOrCreate(
            ['email' => 'admin@bookstore.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Buat akun user contoh
        User::updateOrCreate(
            ['email' => 'user@bookstore.test'],
            [
                'name' => 'John User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Buat kategori buku
        $fiksi = Category::firstOrCreate(['name' => 'Fiksi']);
        $nonfiksi = Category::firstOrCreate(['name' => 'Non-Fiksi']);
        $teknologi = Category::firstOrCreate(['name' => 'Teknologi']);
        $sejarah = Category::firstOrCreate(['name' => 'Sejarah']);
        $bisnis = Category::firstOrCreate(['name' => 'Bisnis']);

        // Buku-buku dummy
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'description' => 'Kisah inspiratif anak-anak Belitung yang berjuang meraih pendidikan.',
                'price' => 85000,
                'stock' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400',
                'category_id' => $fiksi->id,
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'description' => 'Novel sejarah tentang perjuangan dan cinta di era kolonial.',
                'price' => 95000,
                'stock' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400',
                'category_id' => $fiksi->id,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'Panduan membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'price' => 120000,
                'stock' => 30,
                'image_url' => 'https://images.unsplash.com/photo-1589998059171-988d887df646?w=400',
                'category_id' => $nonfiksi->id,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'description' => 'Panduan menulis kode yang bersih dan mudah dipelihara.',
                'price' => 200000,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400',
                'category_id' => $teknologi->id,
            ],
            [
                'title' => 'Laravel Up & Running',
                'author' => 'Matt Stauffer',
                'description' => 'Buku panduan lengkap framework Laravel dari dasar hingga mahir.',
                'price' => 180000,
                'stock' => 12,
                'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400',
                'category_id' => $teknologi->id,
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'description' => 'Sejarah singkat umat manusia dari zaman purba hingga modern.',
                'price' => 135000,
                'stock' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400',
                'category_id' => $sejarah->id,
            ],
            [
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert Kiyosaki',
                'description' => 'Pelajaran finansial yang tidak diajarkan di sekolah.',
                'price' => 110000,
                'stock' => 18,
                'image_url' => 'https://images.unsplash.com/photo-1553729459-afe8f2e2ed65?w=400',
                'category_id' => $bisnis->id,
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'description' => 'Bagaimana entrepreneur modern membangun bisnis secara efisien.',
                'price' => 145000,
                'stock' => 8,
                'image_url' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=400',
                'category_id' => $bisnis->id,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['title' => $book['title']],
                $book
            );
        }
    }
}
