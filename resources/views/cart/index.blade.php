@extends('layouts.store')
@section('title', 'Keranjang Belanja')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6 flex items-center"><i class="fa-solid fa-shopping-cart text-amber-500 mr-3"></i>Keranjang Belanja</h1>
    @if ($cartItems->isNotEmpty())
        @php($paymentMethods = \App\Models\Order::PAYMENT_METHODS)
        {{-- Satu form membungkus daftar buku + alamat kirim + tombol checkout --}}
        <form action="{{ route('checkout') }}" method="POST"
              data-confirm="Pesanan akan dibuat menggunakan metode pembayaran simulasi yang dipilih. Tidak ada transaksi atau pemotongan saldo nyata."
              data-confirm-title="Konfirmasi Checkout"
              data-confirm-ok="Ya, Checkout Sekarang"
              data-confirm-cancel="Kembali ke Keranjang"
              data-confirm-variant="primary">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- ================= KOLOM KIRI: DAFTAR BUKU + ALAMAT ================= --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Daftar buku --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase">
                                        <th class="p-4">Buku</th><th class="p-4">Harga</th><th class="p-4 text-center">Jumlah</th><th class="p-4">Subtotal</th><th class="p-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($cartItems as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="p-4 flex items-center space-x-3">
                                                <img src="{{ $item->book->image_url ?: asset('images/books/default.svg') }}" alt="{{ $item->book->title }}" class="w-12 h-16 object-cover rounded shadow-sm">
                                                <div>
                                                    <a href="{{ route('books.show', $item->book->id) }}" class="font-bold text-gray-900 hover:text-amber-600 transition">{{ $item->book->title }}</a>
                                                    <div class="text-xs text-gray-400">{{ $item->book->category->name }}</div>
                                                </div>
                                            </td>
                                            <td class="p-4 font-semibold text-gray-700">Rp {{ number_format($item->book->price, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center">
                                                {{-- Form update terpisah (bukan bagian dari form checkout) --}}
                                                <div class="inline-flex items-center border border-gray-200 rounded">
                                                    <input form="formUpdate{{ $item->id }}" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->book->stock }}" class="w-14 p-1 text-center text-sm font-semibold focus:outline-none">
                                                    <button form="formUpdate{{ $item->id }}" type="submit" title="Update" class="px-2 py-1 bg-gray-100 hover:bg-amber-500 hover:text-white text-xs transition"><i class="fa-solid fa-arrows-rotate"></i></button>
                                                </div>
                                            </td>
                                            <td class="p-4 font-bold text-amber-600">Rp {{ number_format($item->book->price * $item->quantity, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center">
                                                <button form="formHapus{{ $item->id }}" type="submit"
                                                    data-confirm="Hapus &quot;{{ $item->book->title }}&quot; dari keranjang?"
                                                    data-confirm-title="Hapus Item"
                                                    data-confirm-ok="Ya, Hapus"
                                                    class="text-red-500 hover:text-red-700 p-1"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Form alamat pengiriman --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-base font-bold text-gray-900 flex items-center mb-1">
                            <i class="fa-solid fa-location-dot text-amber-500 mr-2"></i>Alamat Pengiriman
                        </h2>
                        <p class="text-xs text-gray-500 mb-5">Lengkapi alamat rumah Anda. Pilihan pembayaran di bawah hanya simulasi dan tidak terhubung ke layanan pembayaran nyata.</p>

                        @if ($errors->any())
                            <div class="mb-5 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-xs">
                                <p class="font-semibold mb-1">Periksa kembali data pengiriman:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Nama penerima --}}
                            <div>
                                <label for="recipient_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-user"></i></span>
                                    <input id="recipient_name" type="text" name="recipient_name" value="{{ old('recipient_name', $lastOrder->recipient_name ?? auth()->user()->name) }}" required
                                           placeholder="Nama lengkap penerima"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-gray-900 placeholder-gray-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('recipient_name') border-red-400 @else border-gray-300 @enderror">
                                </div>
                            </div>

                            {{-- Nomor telepon --}}
                            <div>
                                <label for="recipient_phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-phone"></i></span>
                                    <input id="recipient_phone" type="text" name="recipient_phone" value="{{ old('recipient_phone', $lastOrder->recipient_phone ?? '') }}" required
                                           placeholder="Contoh: 0812xxxxxxx"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-gray-900 placeholder-gray-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('recipient_phone') border-red-400 @else border-gray-300 @enderror">
                                </div>
                            </div>

                            {{-- Alamat lengkap --}}
                            <div class="sm:col-span-2">
                                <label for="shipping_address" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap Rumah <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none text-gray-400"><i class="fa-solid fa-house"></i></span>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" required
                                              placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan"
                                              class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-gray-900 placeholder-gray-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 resize-none @error('shipping_address') border-red-400 @else border-gray-300 @enderror">{{ old('shipping_address', $lastOrder->shipping_address ?? '') }}</textarea>
                                </div>
                            </div>

                            {{-- Kota --}}
                            <div>
                                <label for="city" class="block text-sm font-semibold text-gray-700 mb-1.5">Kota / Kabupaten <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-city"></i></span>
                                    <input id="city" type="text" name="city" value="{{ old('city', $lastOrder->city ?? '') }}" required
                                           placeholder="Contoh: Bandung"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-gray-900 placeholder-gray-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('city') border-red-400 @else border-gray-300 @enderror">
                                </div>
                            </div>

                            {{-- Kode pos --}}
                            <div>
                                <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Pos <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-envelope-open-text"></i></span>
                                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $lastOrder->postal_code ?? '') }}" required
                                           placeholder="Contoh: 40123"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border bg-white text-sm text-gray-900 placeholder-gray-400 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('postal_code') border-red-400 @else border-gray-300 @enderror">
                                </div>
                            </div>
                        </div>

                        @if ($lastOrder)
                            <p class="mt-4 text-xs text-gray-400 flex items-center">
                                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Alamat diisi otomatis dari pesanan terakhir Anda. Silakan ubah jika berbeda.
                            </p>
                        @endif
                    </div>

                    {{-- Metode pembayaran simulasi --}}
                    <fieldset class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <legend class="text-base font-bold text-gray-900 flex items-center mb-1">
                            <i class="fa-solid fa-credit-card text-amber-500 mr-2"></i>Metode Pembayaran
                        </legend>
                        <p class="text-xs text-gray-500 mb-5">Pilih salah satu untuk simulasi checkout. Tidak ada uang yang ditagihkan.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ($paymentMethods as $value => $method)
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="payment_method" value="{{ $value }}"
                                           data-payment-method required
                                           {{ old('payment_method', \App\Models\Order::DEFAULT_PAYMENT_METHOD) === $value ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <span class="flex h-full flex-col rounded-xl border-2 border-gray-200 bg-white p-4 transition hover:border-amber-300 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500 peer-focus-visible:ring-offset-2">
                                        <span class="mb-3 flex items-center justify-between">
                                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700 peer-checked:bg-amber-100 peer-checked:text-amber-700">
                                                <i class="fa-solid {{ $method['icon'] }}"></i>
                                            </span>
                                            <i data-payment-check class="fa-solid fa-circle-check text-amber-500 opacity-0 transition"></i>
                                        </span>
                                        <span data-payment-label class="text-sm font-bold text-gray-900">{{ $method['label'] }}</span>
                                        <span class="mt-1 text-xs leading-relaxed text-gray-500">{{ $method['description'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method')
                            <p class="mt-3 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>

                {{-- ================= KOLOM KANAN: RINGKASAN ================= --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit lg:sticky lg:top-24">
                    <h2 class="text-base font-bold text-gray-900 border-b pb-3 mb-4">Ringkasan Pesanan</h2>
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between text-gray-600"><span>Total Item</span><span class="font-semibold">{{ $cartItems->sum('quantity') }} buku</span></div>
                        <div class="flex items-start justify-between gap-4 text-gray-600"><span>Metode Pembayaran</span><span id="selected-payment-label" class="text-right font-semibold text-amber-600">Bayar di Tempat (Simulasi COD)</span></div>
                        <div class="border-t pt-3 flex justify-between text-base font-extrabold text-gray-900"><span>Total Pembayaran</span><span class="text-amber-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span></div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-lg text-xs text-amber-800 mb-6 flex items-start space-x-2">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                        <span>Pesanan hanya dicatat sebagai simulasi. Tidak ada pembayaran nyata, pemotongan saldo, atau koneksi ke gateway.</span>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow-md transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-clipboard-check"></i><span>Konfirmasi Simulasi Checkout</span>
                    </button>
                </div>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const paymentInputs = document.querySelectorAll('[data-payment-method]');
                const selectedLabel = document.getElementById('selected-payment-label');
                if (!paymentInputs.length || !selectedLabel) return;

                function updatePaymentSummary() {
                    paymentInputs.forEach(function (input) {
                        const check = input.closest('label').querySelector('[data-payment-check]');
                        check.classList.toggle('opacity-0', !input.checked);
                        check.classList.toggle('opacity-100', input.checked);
                    });

                    const selected = document.querySelector('[data-payment-method]:checked');
                    selectedLabel.textContent = selected
                        ? selected.closest('label').querySelector('[data-payment-label]').textContent
                        : 'Pilih metode pembayaran';
                }

                paymentInputs.forEach(function (input) {
                    input.addEventListener('change', updatePaymentSummary);
                });
                updatePaymentSummary();
            });
        </script>

        {{-- Form bantu (update & hapus item) di luar form checkout untuk menghindari nesting --}}
        @foreach ($cartItems as $item)
            <form id="formUpdate{{ $item->id }}" action="{{ route('cart.update', $item) }}" method="POST" class="hidden">
                @csrf @method('PUT')
            </form>
            <form id="formHapus{{ $item->id }}" action="{{ route('cart.destroy', $item) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <i class="fa-solid fa-cart-shopping text-4xl text-gray-300 mb-3"></i>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Keranjang Belanja Anda Kosong</h2>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm shadow transition">Jelajahi Katalog Buku</a>
        </div>
    @endif
</div>
@endsection
