@extends('layouts.admin')
@section('title', 'Tambah Buku')
@section('header', 'Tambah Buku Baru')

@section('content')
<div class="max-w-3xl bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form action="{{ route('admin.books.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Buku</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') border-red-500 @else border-gray-300 @enderror">
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">Penulis</label>
                <input type="text" name="author" id="author" value="{{ old('author') }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('author') border-red-500 @else border-gray-300 @enderror">
                @error('author') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('category_id') border-red-500 @else border-gray-300 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('price') border-red-500 @else border-gray-300 @enderror">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}" required min="0"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('stock') border-red-500 @else border-gray-300 @enderror">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label for="image_url" class="block text-sm font-semibold text-gray-700 mb-2">URL Gambar Cover (Unsplash/Web)</label>
            <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..."
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('image_url') border-red-500 @else border-gray-300 @enderror">
            <p class="text-xs text-gray-500 mt-1">Gunakan link gambar bebas royalti (Unsplash, dll). Tanpa perlu upload file.</p>
            @error('image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Sinopsis Buku</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('description') border-red-500 @else border-gray-300 @enderror">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                <i class="fa-solid fa-save mr-2"></i> Simpan Buku
            </button>
            <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
