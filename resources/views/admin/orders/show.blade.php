@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Order Items --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Order Items</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Product</th>
                        <th class="px-5 py-3 text-left">Size/Color</th>
                        <th class="px-5 py-3 text-left">Qty</th>
                        <th class="px-5 py-3 text-left">Price</th>
                        <th class="px-5 py-3 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->product_name }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $item->size ?? '-' }} / {{ $item->color ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-gray-600">৳{{ number_format($item->price) }}</td>
                            <td class="px-5 py-3 font-semibold">৳{{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span><span>৳{{ number_format($order->subtotal) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Discount ({{ $order->coupon_code }})</span>
                        <span>−৳{{ number_format($order->discount) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 border-t pt-2">
                    <span>Total</span><span>৳{{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Shipping Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-400 text-xs">Name</p><p class="font-medium">{{ $order->shipping_name }}</p></div>
                <div><p class="text-gray-400 text-xs">Email</p><p class="font-medium">{{ $order->shipping_email }}</p></div>
                <div><p class="text-gray-400 text-xs">Phone</p><p class="font-medium">{{ $order->shipping_phone ?? 'N/A' }}</p></div>
                <div><p class="text-gray-400 text-xs">City</p><p class="font-medium">{{ $order->shipping_city }}</p></div>
                <div class="col-span-2"><p class="text-gray-400 text-xs">Address</p><p class="font-medium">{{ $order->shipping_address }}</p></div>
                @if($order->notes)
                    <div class="col-span-2"><p class="text-gray-400 text-xs">Notes</p><p class="font-medium">{{ $order->notes }}</p></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        {{-- Status Update --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Update Status</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="w-full bg-gray-900 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Order Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Order Summary</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Order Number</span><span class="font-medium">{{ $order->order_number }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Date</span><span>{{ $order->created_at->format('M d, Y') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Customer</span><span>{{ $order->user->name ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Payment</span><span class="text-green-600 font-medium">Cash on Delivery</span></div>
            </div>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="block text-center text-sm text-gray-400 hover:text-gray-700 py-2">← Back to Orders</a>
    </div>
</div>
@endsection