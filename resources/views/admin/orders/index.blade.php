@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search order number or customer..."
               class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 w-72">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
            <option value="">All Status</option>
            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Order</th>
                <th class="px-5 py-3 text-left">Customer</th>
                <th class="px-5 py-3 text-left">Items</th>
                <th class="px-5 py-3 text-left">Total</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Date</th>
                <th class="px-5 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-5 py-3">
                        <p class="text-gray-800 font-medium">{{ $order->shipping_name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->shipping_email }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $order->items->count() }} items</td>
                    <td class="px-5 py-3 font-semibold">৳{{ number_format($order->total) }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
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
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-500 hover:text-blue-700 text-xs font-medium">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
</div>
@endsection