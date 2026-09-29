@extends('layouts.admin')
@section('title', 'Katalog Buku')
@section('header', 'Kelola Data Buku')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Buku</h2>
            <p class="text-sm text-gray-500">Total {{ $books->total() }} judul buku terdaftar</p>
        </div>
        <button onclick="openModal('modalTambahBuku')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Buku Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <th class="p-4 w-12">No</th>
                    <th class="p-4 w-16">Cover</th>
                    <th class="p-4">Judul & Penulis</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($books as $index => $book)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $books->firstItem() + $index }}</td>
                        <td class="p-4">
                            <img src="{{ $book->image_url }}"
                                 alt="{{ $book->title }}"
                                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400';"
                                 class="w-10 h-14 object-cover rounded shadow-sm">
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $book->title }}</div>
                            <div class="text-xs text-gray-500">Penulis: {{ $book->author }}</div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ $book->category->name }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-gray-900">Rp {{ number_format($book->price, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $book->stock > 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $book->stock }} pcs
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick="openEditBuku({{ $book->id }})"
                                        class="text-blue-500 hover:text-blue-700 p-1" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" onclick="openDeleteBuku({{ $book->id }})"
                                        class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-400">
                            <i class="fa-solid fa-book-open text-3xl mb-2 block"></i>
                            Belum ada buku. Klik "Tambah Buku Baru" untuk memulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($books->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $books->links() }}</div>
    @endif
</div>

{{-- ==================== MODAL TAMBAH BUKU ==================== --}}
<div id="modalTambahBuku" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
            <h3 class="text-base font-bold text-gray-900 flex items-center">
                <i class="fa-solid fa-book text-blue-600 mr-2"></i> Tambah Buku Baru
            </h3>
            <button onclick="closeModal('modalTambahBuku')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           placeholder="Masukkan judul buku..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" required value="{{ old('author') }}"
                           placeholder="Nama penulis buku..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                    @error('author') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" required min="0" value="{{ old('price') }}"
                               placeholder="85000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" required min="0" value="{{ old('stock', 1) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Input Cover: File Upload (PNG, SVG, dll) atau URL --}}
            <div class="mb-4 p-3.5 bg-slate-50 border border-gray-200 rounded-lg">
                <label class="block text-xs font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-blue-600 mr-1"></i> Gambar Cover Buku
                </label>
                <div class="space-y-2.5">
                    <div>
                        <span class="text-[11px] font-medium text-gray-600 block mb-1">
                            <i class="fa-solid fa-upload mr-1 text-gray-400"></i>Opsi A: Upload File (PNG, SVG, JPG, JPEG, WEBP - Maks. 5MB)
                        </span>
                        <input type="file" name="image_file" accept=".png,.svg,.jpg,.jpeg,.webp,.gif"
                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        @error('image_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                        <span class="flex-1 border-t border-gray-200"></span>
                        <span class="text-[10px] uppercase font-bold text-gray-400">atau</span>
                        <span class="flex-1 border-t border-gray-200"></span>
                    </div>
                    <div>
                        <span class="text-[11px] font-medium text-gray-600 block mb-1">
                            <i class="fa-solid fa-link mr-1 text-gray-400"></i>Opsi B: Gunakan URL Gambar (Unsplash / Web)
                        </span>
                        <input type="url" name="image_url" value="{{ old('image_url') }}"
                               placeholder="https://images.unsplash.com/..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-xs">
                        @error('image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi / Sinopsis</label>
                <textarea name="description" rows="3" placeholder="Sinopsis singkat buku..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalTambahBuku')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-save mr-1"></i> Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL EDIT BUKU ==================== --}}
<div id="modalEditBuku" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
            <h3 class="text-base font-bold text-gray-900 flex items-center">
                <i class="fa-solid fa-pen-to-square text-amber-500 mr-2"></i> Edit Data Buku
            </h3>
            <button onclick="closeModal('modalEditBuku')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="formEditBuku" action="" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="edit_title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="edit_author" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="edit_category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="edit_price" required min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="edit_stock" required min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                    </div>
                </div>
            </div>

            {{-- Input Edit Cover: Preview + Upload File Baru atau Ganti URL --}}
            <div class="mb-4 p-3.5 bg-slate-50 border border-gray-200 rounded-lg">
                <label class="block text-xs font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-amber-600 mr-1"></i> Gambar Cover Buku
                </label>
                <div class="flex items-start space-x-4 mb-2">
                    <div class="flex-shrink-0">
                        <span class="text-[10px] text-gray-400 block mb-1">Cover Saat Ini:</span>
                        <img id="edit_cover_preview" src="" alt="Preview Cover" class="w-14 h-20 object-cover rounded shadow-sm border border-gray-200 bg-white">
                    </div>
                    <div class="flex-1 space-y-2">
                        <div>
                            <span class="text-[11px] font-medium text-gray-600 block mb-1">
                                <i class="fa-solid fa-upload mr-1 text-gray-400"></i>Opsi A: Upload File Baru (PNG, SVG, JPG, WEBP - Maks. 5MB)
                            </span>
                            <input type="file" name="image_file" id="edit_image_file" accept=".png,.svg,.jpg,.jpeg,.webp,.gif"
                                   class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                        </div>
                        <div class="flex items-center space-x-2 text-xs text-gray-400">
                            <span class="flex-1 border-t border-gray-200"></span>
                            <span class="text-[10px] uppercase font-bold text-gray-400">atau</span>
                            <span class="flex-1 border-t border-gray-200"></span>
                        </div>
                        <div>
                            <span class="text-[11px] font-medium text-gray-600 block mb-1">
                                <i class="fa-solid fa-link mr-1 text-gray-400"></i>Opsi B: Ganti dengan URL Gambar
                            </span>
                            <input type="url" name="image_url" id="edit_image_url"
                                   placeholder="https://images.unsplash.com/..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs">
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 italic">Kosongkan jika ingin tetap mempertahankan gambar cover saat ini.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi / Sinopsis</label>
                <textarea name="description" id="edit_description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalEditBuku')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-save mr-1"></i> Perbarui Buku
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL HAPUS BUKU ==================== --}}
<div id="modalHapusBuku" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Buku?</h3>
            <p class="text-sm text-gray-500 mb-1">Anda akan menghapus buku:</p>
            <p id="deleteNamaBuku" class="text-sm font-bold text-red-600 mb-4"></p>
            <p class="text-xs text-gray-400 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <form id="formHapusBuku" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('modalHapusBuku')"
                            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                        <i class="fa-solid fa-trash mr-1"></i> Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', function(){ openModal('modalTambahBuku'); });</script>
@endif

<script>
const booksData = @json($books->getCollection()->keyBy('id'));

function openModal(id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.body.style.overflow = '';
}

function openEditBuku(id) {
    const book = booksData[id];
    if (!book) {
        console.error('Data buku tidak ditemukan untuk ID:', id);
        return;
    }

    const form = document.getElementById('formEditBuku');
    form.action = '{{ url("admin/books") }}/' + id;

    document.getElementById('edit_title').value       = book.title || '';
    document.getElementById('edit_author').value      = book.author || '';
    document.getElementById('edit_price').value       = Math.round(Number(book.price)) || 0;
    document.getElementById('edit_stock').value       = book.stock !== undefined ? book.stock : 0;

    // Tampilkan preview cover saat ini
    const preview = document.getElementById('edit_cover_preview');
    if (preview) {
        preview.src = book.image_url || 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400';
    }

    // Reset file input
    const fileInput = document.getElementById('edit_image_file');
    if (fileInput) fileInput.value = '';

    // Jika gambar adalah URL eksternal (Unsplash dll), isi di input URL
    const isExternalUrl = book.image_url && (book.image_url.startsWith('http://') || book.image_url.startsWith('https://')) && !book.image_url.includes('/storage/books/');
    document.getElementById('edit_image_url').value = isExternalUrl ? book.image_url : '';

    document.getElementById('edit_description').value = book.description || '';

    const sel = document.getElementById('edit_category_id');
    if (sel) {
        sel.value = book.category_id;
    }

    openModal('modalEditBuku');
}

function openDeleteBuku(id) {
    const book = booksData[id];
    const title = book ? book.title : 'buku ini';
    document.getElementById('formHapusBuku').action = '{{ url("admin/books") }}/' + id;
    document.getElementById('deleteNamaBuku').textContent = '"' + title + '"';
    openModal('modalHapusBuku');
}

['modalTambahBuku','modalEditBuku','modalHapusBuku'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    }
});
</script>
@endsection
