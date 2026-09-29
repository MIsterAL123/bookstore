<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

/**
 * Admin MessageController mengelola kotak pesan masuk dari pengguna.
 */
class MessageController extends Controller
{
    /**
     * Tampilkan semua pesan masuk dari pengguna, urut terbaru.
     */
    public function index()
    {
        $messages = Message::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Hapus pesan yang dipilih dari database.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
