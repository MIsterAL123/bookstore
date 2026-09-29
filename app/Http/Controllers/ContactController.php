<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ContactController mengelola form kontak user ke admin.
 */
class ContactController extends Controller
{
    /**
     * Tampilkan form kontak. Jika belum login, tampilkan pesan ajakan login.
     */
    public function create()
    {
        return view('contact');
    }

    /**
     * Simpan pesan dari user ke tabel messages.
     * Hanya user yang sudah login yang bisa mengirim pesan.
     */
    public function store(Request $request)
    {
        $this->middleware('auth');

        $request->validate([
            'content' => 'required|string|min:10|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda berhasil terkirim ke admin. Kami akan segera merespons!');
    }
}
