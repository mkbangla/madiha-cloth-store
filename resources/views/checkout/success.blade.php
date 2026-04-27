@extends('layouts.app')
@section('title', 'Order Placed!')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-16 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check text-green-500 text-3xl"></i>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Placed!</h1>
    <p class="text-gray-500 mb-2">Thank you for your order.</p>
    <p class="text-gray-400 text-sm mb-8">Order number: <span class="font-mono font-bold text-gray-700">{{ $order->order_number }}</span></p>

    <div class="bg-gray-50 rounded-2xl p-6 text-left mb-8">
        <h3 class="font-semibold text-gray-800 mb-4">Order Summary</h3>
        <div class="space-y-3">
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span class="font-medium">৳{{ number_format($item->subtotal) }}</span>
                </div>
            @endforeach
        </div>
        <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span><span>৳{{ number_format($order->subtotal) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between text-sm text-green-600">
                    <span>Discount</span><span>−৳{{ number_format($order->discount) }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-gray-900 border-t pt-2">
                <span>Total</span><span>৳{{ number_format($order->total) }}</span>
            </div>
        </div>
    </div>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('account.index') }}" class="btn-primary">View My Orders</a>
        <a href="{{ route('shop') }}" class="btn-outline">Continue Shopping</a>
    </div>
</div>
@endsection