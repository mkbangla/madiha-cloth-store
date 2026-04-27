@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">My Orders</h1>

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize
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
                            <p class="font-bold text-gray-900 mt-1">৳{{ number_format($order->total) }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                        <p class="text-sm text-gray-500">{{ $order->items->count() }} item(s)</p>
                        <a href="{{ route('account.order.show', $order) }}"
                           class="text-sm text-gray-700 font-medium hover:text-gray-900 underline">
                            View Details →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    @else
        <div class="text-center py-20">
            <i class="fas fa-bag-shopping text-5xl text-gray-200 mb-6"></i>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">No orders yet</h2>
            <p class="text-gray-400 mb-8">Start shopping to see your orders here.</p>
            <a href="{{ route('shop') }}" class="btn-primary">Shop Now</a>
        </div>
    @endif
</div>
@endsection