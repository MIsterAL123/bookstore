@extends('errors.layout')

@php
    $code = 404;
    $heading = 'Halaman Tidak Ditemukan';
    $icon = 'fa-magnifying-glass';
    $message = 'Halaman yang Anda cari tidak ada atau sudah dipindahkan.';
    $reasons = [
        'Alamat yang Anda masukkan mungkin salah ketik.',
        'Buku atau kategori yang Anda cari sudah dihapus dari katalog.',
    ];
    $hint = 'Coba jelajahi katalog dari beranda untuk menemukan buku yang Anda cari.';
@endphp
