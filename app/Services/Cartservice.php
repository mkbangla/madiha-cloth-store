<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class CartService
{
    protected string $sessionKey = 'madia_cart';

    public function getCart(): array
    {
        return session($this->sessionKey, []);
    }

    public function add(Product $product, int $quantity = 1, ?string $size = null, ?string $color = null): void
    {
        $cart  = $this->getCart();
        $rowId = $this->generateRowId($product->id, $size, $color);

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $quantity;
        } else {
            $cart[$rowId] = [
                'row_id'     => $rowId,
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->current_price,
                'quantity'   => $quantity,
                'size'       => $size,
                'color'      => $color,
                'image'      => $product->image_url,
                'slug'       => $product->slug,
            ];
        }

        session([$this->sessionKey => $cart]);
    }

    public function update(string $rowId, int $quantity): void
    {
        $cart = $this->getCart();

        if (isset($cart[$rowId])) {
            if ($quantity <= 0) {
                unset($cart[$rowId]);
            } else {
                $cart[$rowId]['quantity'] = $quantity;
            }
            session([$this->sessionKey => $cart]);
        }
    }

    public function remove(string $rowId): void
    {
        $cart = $this->getCart();
        unset($cart[$rowId]);
        session([$this->sessionKey => $cart]);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
        session()->forget('coupon');
    }

    public function count(): int
    {
        return array_sum(array_column($this->getCart(), 'quantity'));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(
            fn($item) => $item['price'] * $item['quantity'],
            $this->getCart()
        ));
    }

    public function discount(): float
    {
        $coupon = session('coupon');
        if (!$coupon) return 0;
        return $coupon['discount'];
    }

    public function total(): float
    {
        return max(0, $this->subtotal() - $this->discount());
    }

    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    protected function generateRowId(int $productId, ?string $size, ?string $color): string
    {
        return md5($productId . '-' . $size . '-' . $color);
    }
}