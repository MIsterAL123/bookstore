@extends('errors.layout')

@php
    $code = 503;
    $heading = 'Sedang Dalam Perbaikan';
    $icon = 'fa-screwdriver-wrench';
    $message = 'BookStore sedang menjalani pemeliharaan singkat.';
    $reasons = [
        'Sistem sedang diperbarui untuk meningkatkan layanan.',
        'Akses sementara ditutup agar data tetap aman.',
    ];
    $hint = 'Silakan kembali beberapa saat lagi.';
@endphp
