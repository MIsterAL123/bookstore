@extends('layouts.admin')
@section('title', 'Kategori Buku')
@section('header', 'Kelola Kategori Buku')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Kategori</h2>
            <p class="text-sm text-gray-500">Total {{ $categories->total() }} kategori terdaftar</p>
        </div>
        <button onclick="openModal('modalTambah')" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <th class="p-4 w-16">No</th>
                    <th class="p-4">Nama Kategori</th>
                    <th class="p-4">Jumlah Buku</th>
                    <th class="p-4">Dibuat Pada</th>
                    <th class="p-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($categories as $index => $category)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $categories->firstItem() + $index }}</td>
                        <td class="p-4 font-semibold text-gray-900">{{ $category->name }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $category->books_count }} buku
                            </span>
                        </td>
                        <td class="p-4 text-gray-500">{{ $category->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button"
                                    onclick="openEditModal({{ $category->id }})"
                                    class="text-amber-500 hover:text-amber-600 p-1" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button"
                                    onclick="openDeleteModal({{ $category->id }})"
                                    class="text-red-500 hover:text-red-600 p-1" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400">
                            <i class="fa-solid fa-tags text-3xl mb-2 block"></i>
                            Belum ada kategori. Klik "Tambah Kategori" untuk memulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>

{{-- ==================== MODAL TAMBAH ==================== --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 animate-fade-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900 flex items-center">
                <i class="fa-solid fa-tags text-amber-500 mr-2"></i> Tambah Kategori Baru
            </h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-5">
                <label for="name_tambah" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name_tambah" required
                       placeholder="Contoh: Novel, Sains, Teknologi..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none text-sm">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('modalTambah')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL EDIT ==================== --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900 flex items-center">
                <i class="fa-solid fa-pen-to-square text-blue-500 mr-2"></i> Edit Kategori
            </h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="name_edit" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name_edit" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-save mr-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL HAPUS ==================== --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Kategori?</h3>
            <p class="text-sm text-gray-500 mb-1">Anda akan menghapus kategori:</p>
            <p id="deleteNamaKategori" class="text-sm font-bold text-red-600 mb-4"></p>
            <p class="text-xs text-gray-400 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <form id="formHapus" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('modalHapus')"
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

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', function(){ /* flash handled by layout */ });</script>
@endif

{{-- Buka modal tambah otomatis jika ada error validasi --}}
@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', function(){ openModal('modalTambah'); });</script>
@endif

<script>
const categoriesData = @json($categories->getCollection()->keyBy('id'));

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

function openEditModal(id) {
    const cat = categoriesData[id];
    if (!cat) return;
    document.getElementById('formEdit').action = '{{ url("admin/categories") }}/' + id;
    document.getElementById('name_edit').value = cat.name || '';
    openModal('modalEdit');
}

function openDeleteModal(id) {
    const cat = categoriesData[id];
    const nama = cat ? cat.name : 'kategori ini';
    document.getElementById('formHapus').action = '{{ url("admin/categories") }}/' + id;
    document.getElementById('deleteNamaKategori').textContent = '"' + nama + '"';
    openModal('modalHapus');
}

// Tutup modal jika klik di luar
['modalTambah','modalEdit','modalHapus'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    }
});
</script>
@endsection
