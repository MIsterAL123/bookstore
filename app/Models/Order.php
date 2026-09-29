<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const DEFAULT_PAYMENT_METHOD = 'Payment at Delivery';

    public const PAYMENT_METHODS = [
        self::DEFAULT_PAYMENT_METHOD => [
            'label' => 'Bayar di Tempat (Simulasi COD)',
            'description' => 'Tidak ada pembayaran nyata. Pesanan hanya dicatat sebagai simulasi COD.',
            'icon' => 'fa-truck-fast',
        ],
        'Dummy Bank Transfer' => [
            'label' => 'Transfer Bank (Simulasi)',
            'description' => 'Tidak ada rekening tujuan dan tidak ada uang yang dikirim.',
            'icon' => 'fa-building-columns',
        ],
        'Dummy E-Wallet' => [
            'label' => 'E-Wallet (Simulasi)',
            'description' => 'Tidak terhubung ke aplikasi atau saldo e-wallet nyata.',
            'icon' => 'fa-wallet',
        ],
    ];

    protected $fillable = [
        'user_id',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'postal_code',
        'payment_method',
        'status',
        'total_price',
    ];

    public static function paymentMethodLabel(string $method): string
    {
        return self::PAYMENT_METHODS[$method]['label'] ?? $method;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::paymentMethodLabel($this->payment_method);
    }

    /**
     * Relasi pesanan ke user pemesan
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke daftar item buku dalam pesanan
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
