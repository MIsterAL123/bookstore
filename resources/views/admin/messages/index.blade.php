@extends('layouts.admin')
@section('title', 'Pesan Masuk')
@section('header', 'Pesan Masuk dari Pengguna')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800">Daftar Pesan Masuk</h2>
        <p class="text-sm text-gray-500">Pesan dan kritik/saran dari pelanggan melalui form Contact to Admin</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <th class="p-4 w-16">No</th><th class="p-4">Pengirim</th><th class="p-4">Isi Pesan</th><th class="p-4">Waktu Dikirim</th><th class="p-4 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($messages as $index => $msg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $messages->firstItem() + $index }}</td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $msg->user->name ?? 'User Anonim' }}</div>
                            <div class="text-xs text-gray-500">{{ $msg->user->email ?? '-' }}</div>
                        </td>
                        <td class="p-4 text-gray-700 max-w-md whitespace-pre-line">{{ $msg->content }}</td>
                        <td class="p-4 text-gray-500 text-xs">{{ $msg->created_at->format('d M Y H:i') }}</td>
                        <td class="p-4 text-center">
                            <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST"
                                  data-confirm="Pesan dari {{ $msg->user->name ?? 'User Anonim' }} akan dihapus permanen."
                                  data-confirm-title="Hapus Pesan"
                                  data-confirm-ok="Ya, Hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Hapus Pesan"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-500">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-gray-300"></i>
                            <p class="font-medium">Belum ada pesan masuk dari pengguna.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($messages->hasPages()) <div class="p-4 border-t border-gray-100">{{ $messages->links() }}</div> @endif
</div>
@endsection
