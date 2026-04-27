<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'discount', 'total', 'coupon_code',
        'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country', 'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'badge-warning',
            'processing' => 'badge-info',
            'shipped'    => 'badge-primary',
            'delivered'  => 'badge-success',
            'cancelled'  => 'badge-danger',
            default      => 'badge-secondary',
        };
    }
}