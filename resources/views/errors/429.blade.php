@extends('errors.layout')

@php
    $code = 429;
    $heading = 'Terlalu Banyak Permintaan';
    $icon = 'fa-hourglass-half';
    $message = 'Anda mengirim permintaan terlalu sering dalam waktu singkat.';
    $reasons = [
        'Tombol diklik berulang kali dalam waktu cepat.',
        'Halaman dimuat ulang berkali-kali secara beruntun.',
    ];
    $hint = 'Tunggu sebentar, lalu coba lagi.';
@endphp
