<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount', 'max_discount',
        'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active'  => 'boolean',
        'value'      => 'float',
    ];

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsUsageLimitReachedAttribute(): bool
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    public function isValidFor(float $orderAmount): bool
    {
        if (!$this->is_active) return false;
        if ($this->is_expired) return false;
        if ($this->is_usage_limit_reached) return false;
        if ($orderAmount < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        $discount = $this->type === 'percentage'
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return round($discount, 2);
    }
}