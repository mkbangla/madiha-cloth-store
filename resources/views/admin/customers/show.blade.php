@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Customer Details')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Customer Info --}}
    <div class="space-y-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 rounded-full bg-gray-900 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 class="font-semibold text-gray-900 text-lg">{{ $user->name }}</h3>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
            </div>
            <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Phone</span>
                    <span class="font-medium">{{ $user->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined</span>
                    <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Orders</span>
                    <span class="font-medium">{{ $orders->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders --}}
    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Order History</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Order</th>
                        <th class="px-5 py-3 text-left">Items</th>
                        <th class="px-5 py-3 text-left">Total</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="font-medium text-blue-600 hover:text-blue-800">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $order->items->count() }}</td>
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
                            <td class="px-5 py-3 text-gray-400 text-xs">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection