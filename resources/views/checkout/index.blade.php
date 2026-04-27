@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Shipping Form --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-5">Shipping Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                   placeholder="+880 1700-000000">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <textarea name="address" rows="2"
                                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                      placeholder="House, Road, Area" required>{{ old('address') }}</textarea>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                   placeholder="Dhaka" required>
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State / Division</label>
                            <input type="text" name="state" value="{{ old('state') }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                   placeholder="Dhaka Division">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes</label>
                            <textarea name="notes" rows="2"
                                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                      placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-4">Payment Method</h2>
                    <label class="flex items-center gap-3 p-4 border border-gray-900 rounded-xl cursor-pointer">
                        <input type="radio" name="payment" value="cod" checked class="w-4 h-4">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Cash on Delivery</p>
                            <p class="text-xs text-gray-400">Pay when your order arrives</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-gray-50 rounded-2xl p-6 h-fit">
                <h2 class="font-semibold text-gray-900 mb-5">Order Summary</h2>

                <div class="space-y-3 mb-5">
                    @foreach($items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                            <span class="font-medium">৳{{ number_format($item['price'] * $item['quantity']) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($subtotal) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount ({{ $coupon['code'] }})</span>
                            <span>−৳{{ number_format($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-gray-900 text-base border-t pt-3">
                        <span>Total</span>
                        <span>৳{{ number_format($total) }}</span>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-gray-900 text-white py-4 rounded-xl text-sm font-medium hover:bg-gray-700 transition-colors mt-6">
                    Place Order
                </button>

                <a href="{{ route('cart.index') }}" class="block text-center text-sm text-gray-400 hover:text-gray-700 mt-3">
                    ← Back to Cart
                </a>
            </div>
        </div>
    </form>
</div>
@endsection