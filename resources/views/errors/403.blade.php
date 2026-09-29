@extends('errors.layout')

@php
    $code = 403;
    $heading = 'Akses Ditolak';
    $icon = 'fa-lock';
    $message = 'Anda tidak memiliki izin untuk membuka halaman ini.';
    $reasons = [
        'Halaman ini khusus untuk <strong>admin</strong>, sedangkan akun Anda terdaftar sebagai pengguna biasa.',
        'Sesi Anda mungkin sudah berakhir, sehingga hak akses tidak lagi dikenali.',
    ];
    $hint = 'Jika Anda merasa seharusnya punya akses, silakan hubungi administrator toko.';
@endphp
