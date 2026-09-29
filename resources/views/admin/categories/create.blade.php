@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Novel, Sains, Teknologi"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none @error('name') border-red-500 @else border-gray-300 @enderror">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center space-x-3">
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                <i class="fa-solid fa-save mr-2"></i> Simpan Kategori
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
