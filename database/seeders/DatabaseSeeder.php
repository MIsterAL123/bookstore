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
                'image_url' => 'images/books/laskar-pelangi.svg',
                'category_id' => $fiksi->id,
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'description' => 'Novel sejarah tentang perjuangan dan cinta di era kolonial.',
                'price' => 95000,
                'stock' => 15,
                'image_url' => 'images/books/bumi-manusia.svg',
                'category_id' => $fiksi->id,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'Panduan membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'price' => 120000,
                'stock' => 30,
                'image_url' => 'images/books/atomic-habits.svg',
                'category_id' => $nonfiksi->id,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'description' => 'Panduan menulis kode yang bersih dan mudah dipelihara.',
                'price' => 200000,
                'stock' => 10,
                'image_url' => 'images/books/clean-code.svg',
                'category_id' => $teknologi->id,
            ],
            [
                'title' => 'Laravel Up & Running',
                'author' => 'Matt Stauffer',
                'description' => 'Buku panduan lengkap framework Laravel dari dasar hingga mahir.',
                'price' => 180000,
                'stock' => 12,
                'image_url' => 'images/books/laravel-up-running.svg',
                'category_id' => $teknologi->id,
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'description' => 'Sejarah singkat umat manusia dari zaman purba hingga modern.',
                'price' => 135000,
                'stock' => 20,
                'image_url' => 'images/books/sapiens.svg',
                'category_id' => $sejarah->id,
            ],
            [
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert Kiyosaki',
                'description' => 'Pelajaran finansial yang tidak diajarkan di sekolah.',
                'price' => 110000,
                'stock' => 18,
                'image_url' => 'images/books/rich-dad-poor-dad.svg',
                'category_id' => $bisnis->id,
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'description' => 'Bagaimana entrepreneur modern membangun bisnis secara efisien.',
                'price' => 145000,
                'stock' => 8,
                'image_url' => 'images/books/the-lean-startup.svg',
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
