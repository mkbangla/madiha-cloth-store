<?php

// ============================================================
// app/Http/Controllers/CouponController.php
// ============================================================

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function apply(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return back()->with('coupon_error', 'Invalid coupon code.');
        }

        $subtotal = $this->cart->subtotal();

        if (!$coupon->isValidFor($subtotal)) {
            if ($coupon->is_expired) {
                return back()->with('coupon_error', 'This coupon has expired.');
            }
            if ($coupon->is_usage_limit_reached) {
                return back()->with('coupon_error', 'This coupon has reached its usage limit.');
            }
            return back()->with('coupon_error', "Minimum order amount is ৳{$coupon->min_order_amount}.");
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session(['coupon' => [
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]]);

        return back()->with('success', "Coupon applied! You save ৳{$discount}.");
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }
}