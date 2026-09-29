@extends('errors.layout')

@php
    $code = 500;
    $heading = 'Terjadi Kesalahan pada Server';
    $icon = 'fa-triangle-exclamation';
    $message = 'Kami tidak dapat memproses permintaan Anda saat ini. Tim kami sudah menerima laporan kesalahan ini.';
    $reasons = [
        'Ada gangguan sementara pada server atau koneksi database.',
        'Data yang dikirim tidak dapat diproses oleh sistem.',
    ];
    $hint = 'Silakan coba beberapa saat lagi. Jika masih gagal, hubungi administrator.';
@endphp
