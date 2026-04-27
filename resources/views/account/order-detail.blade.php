@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
        <a href="{{ route('account.index') }}" class="text-sm text-gray-400 hover:text-gray-700">← My Orders</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-5">

            {{-- Items --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Items Ordered</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <div class="px-5 py-4 flex justify-between">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    @if($item->size) Size: {{ $item->size }} @endif
                                    @if($item->color) · Color: {{ $item->color }} @endif
                                    · Qty: {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="font-semibold text-gray-900">৳{{ number_format($item->subtotal) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 border-t border-gray-100 space-y-2">
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

            {{-- Shipping --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Shipping Address</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-800">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }}</p>
                    <p>{{ $order->shipping_phone }}</p>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 h-fit">
            <h3 class="font-semibold text-gray-800 mb-4">Order Status</h3>
            <span class="px-3 py-1.5 rounded-full text-sm font-medium capitalize
                {{ match($order->status) {
                    'pending'    => 'bg-yellow-100 text-yellow-700',
                    'processing' => 'bg-blue-100 text-blue-700',
                    'shipped'    => 'bg-purple-100 text-purple-700',
                    'delivered'  => 'bg-green-100 text-green-700',
                    'cancelled'  => 'bg-red-100 text-red-700',
                    default      => 'bg-gray-100 text-gray-700',
                } }}">
                {{ $order->status }}
            </span>
            <div class="mt-4 text-sm text-gray-500 space-y-1">
                <p>Placed: {{ $order->created_at->format('M d, Y h:i A') }}</p>
                <p>Payment: Cash on Delivery</p>
            </div>
        </div>
    </div>
</div>
@endsection