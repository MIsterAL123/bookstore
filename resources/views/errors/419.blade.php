@extends('errors.layout')

@php
    $code = 419;
    $heading = 'Sesi Anda Telah Berakhir';
    $icon = 'fa-clock-rotate-left';
    $message = 'Untuk menjaga keamanan, halaman ini kedaluwarsa setelah Anda keluar dari akun. Silakan masuk kembali untuk melanjutkan aktivitas Anda.';
    $reasons = [
        'Anda menekan tombol keluar (logout) sebelumnya.',
        'Anda membuka kembali halaman ini lewat tombol <strong>kembali</strong> atau dengan memuat ulang halaman (F5 / refresh).',
    ];
    $hint = 'Halaman ini tidak dapat diproses ulang karena token keamanan sudah tidak berlaku.';
    $showLogin = true;
@endphp
