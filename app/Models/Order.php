<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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
