@extends('layouts.app')
@section('title', 'Cart')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if(empty($items))
        <div class="text-center py-24">
            <i class="fas fa-shopping-bag text-5xl text-gray-200 mb-6"></i>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
            <p class="text-gray-400 mb-8">Add some products and come back here.</p>
            <a href="{{ route('shop') }}" class="btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Cart items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $rowId => $item)
                    <div class="flex gap-4 bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                             class="w-24 h-24 object-cover rounded-lg bg-gray-100 flex-shrink-0">

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('product.show', $item['slug']) }}"
                                       class="font-medium text-gray-900 hover:text-gray-600">
                                        {{ $item['name'] }}
                                    </a>
                                    <p class="text-sm text-gray-400 mt-0.5">
                                        @if($item['size'])  Size: {{ $item['size'] }} @endif
                                        @if($item['color']) &nbsp;· Color: {{ $item['color'] }} @endif
                                    </p>
                                </div>
                                <form action="{{ route('cart.remove', $rowId) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-red-400 transition-colors ml-4">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('cart.update', $rowId) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="flex items-center gap-2">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                                    class="w-7 h-7 border border-gray-200 rounded text-sm hover:bg-gray-50 flex items-center justify-center">−</button>
                                            <span class="w-8 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                                    class="w-7 h-7 border border-gray-200 rounded text-sm hover:bg-gray-50 flex items-center justify-center">+</button>
                                        </div>
                                    </form>
                                </div>
                                <span class="font-semibold text-gray-900">
                                    ৳{{ number_format($item['price'] * $item['quantity']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Order summary --}}
            <div class="bg-gray-50 rounded-2xl p-6 h-fit">
                <h2 class="font-semibold text-gray-900 mb-6">Order Summary</h2>

                {{-- Coupon --}}
                @if($coupon)
                    <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg px-3 py-2 mb-4">
                        <div>
                            <span class="text-green-700 text-sm font-medium">{{ $coupon['code'] }}</span>
                            <p class="text-green-600 text-xs">−৳{{ number_format($coupon['discount']) }} saved</p>
                        </div>
                        <form action="{{ route('coupon.remove') }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-green-500 text-xs hover:text-green-700">Remove</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2 mb-4">
                        @csrf
                        <input type="text" name="coupon_code" placeholder="Coupon code"
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <button class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                            Apply
                        </button>
                    </form>
                    @if(session('coupon_error'))
                        <p class="text-red-500 text-xs mb-3">{{ session('coupon_error') }}</p>
                    @endif
                @endif

                {{-- Totals --}}
                <div class="space-y-3 border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($subtotal) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount</span>
                            <span>−৳{{ number_format($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-200 pt-3">
                        <span>Total</span>
                        <span>৳{{ number_format($total) }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}"
                   class="block text-center bg-gray-900 text-white py-4 rounded-xl text-sm font-medium hover:bg-gray-700 transition-colors">
                    Proceed to Checkout
                </a>
                <a href="{{ route('shop') }}"
                   class="block text-center text-sm text-gray-400 hover:text-gray-700 mt-3">
                    ← Continue Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection