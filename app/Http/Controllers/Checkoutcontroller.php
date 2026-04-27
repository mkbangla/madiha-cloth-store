<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items    = $this->cart->getCart();
        $coupon   = session('coupon');
        $subtotal = $this->cart->subtotal();
        $discount = $this->cart->discount();
        $total    = $this->cart->total();

        return view('checkout.index', compact('items', 'coupon', 'subtotal', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'required|string',
            'city'    => 'required|string|max:100',
            'state'   => 'nullable|string|max:100',
            'zip'     => 'nullable|string|max:20',
            'notes'   => 'nullable|string',
        ]);

        $subtotal = $this->cart->subtotal();
        $discount = $this->cart->discount();
        $total    = $this->cart->total();
        $coupon   = session('coupon');

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => 'MCS-' . strtoupper(Str::random(8)),
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'total'            => $total,
                'coupon_code'      => $coupon['code'] ?? null,
                'shipping_name'    => $request->name,
                'shipping_email'   => $request->email,
                'shipping_phone'   => $request->phone,
                'shipping_address' => $request->address,
                'shipping_city'    => $request->city,
                'shipping_state'   => $request->state,
                'shipping_zip'     => $request->zip,
                'shipping_country' => 'BD',
                'notes'            => $request->notes,
            ]);

            foreach ($this->cart->getCart() as $item) {
                $order->items()->create([
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'size'         => $item['size'],
                    'color'        => $item['color'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);

                // Decrement stock
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            // Increment coupon usage
            if ($coupon) {
                Coupon::where('code', $coupon['code'])->increment('used_count');
            }

            DB::commit();
            $this->cart->clear();

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function success(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items');
        return view('checkout.success', compact('order'));
    }
}