@extends('errors.layout')

@php
    $code = 405;
    $heading = 'Metode Tidak Diizinkan';
    $icon = 'fa-ban';
    $message = 'Alamat ini hanya bisa diakses lewat tombol yang tersedia di halaman, bukan dibuka langsung dari address bar.';
    $reasons = [
        'Anda membuka alamat aksi (mis. <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700">/logout</code>) langsung dari address bar.',
        'Anda menekan tombol <strong>kembali</strong> atau memuat ulang halaman setelah melakukan sebuah aksi.',
    ];
    $hint = 'Gunakan tombol di halaman web untuk melakukan aksi, bukan mengetikkan alamatnya langsung.';
@endphp
