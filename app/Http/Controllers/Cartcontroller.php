<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        $items    = $this->cart->getCart();
        $coupon   = session('coupon');
        $subtotal = $this->cart->subtotal();
        $discount = $this->cart->discount();
        $total    = $this->cart->total();

        return view('cart.index', compact('items', 'coupon', 'subtotal', 'discount', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'integer|min:1|max:20',
            'size'     => 'nullable|string',
            'color'    => 'nullable|string',
        ]);

        if ($product->is_out_of_stock) {
            return back()->with('error', 'This product is out of stock.');
        }

        $this->cart->add(
            $product,
            $request->input('quantity', 1),
            $request->input('size'),
            $request->input('color')
        );

        return back()->with('success', '"' . $product->name . '" added to cart!');
    }

    public function update(Request $request, string $rowId)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:20']);
        $this->cart->update($rowId, $request->quantity);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(string $rowId)
    {
        $this->cart->remove($rowId);
        return back()->with('success', 'Item removed from cart.');
    }
}