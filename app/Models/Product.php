<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'stock', 'low_stock_threshold', 'sku',
        'sizes', 'colors', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'sizes'       => 'array',
        'colors'      => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'price'       => 'float',
        'sale_price'  => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock === 0;
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->primaryImage;
        if ($primary) return asset('storage/' . $primary->path);
        $first = $this->images->first();
        if ($first) return asset('storage/' . $first->path);
        return asset('images/placeholder-product.jpg');
    }
}