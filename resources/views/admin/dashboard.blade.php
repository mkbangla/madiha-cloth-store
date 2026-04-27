@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">৳{{ number_format($totalRevenue) }}</p>
        <p class="text-xs text-green-600 mt-1">৳{{ number_format($monthRevenue) }} this month</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">Total Orders</p>
            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bag-shopping text-blue-600 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
        <p class="text-xs text-blue-600 mt-1">{{ $monthOrders }} this month</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">Total Products</p>
            <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shirt text-purple-600 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
        <p class="text-xs text-red-500 mt-1">{{ $outOfStockCount }} out of stock</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">Customers</p>
            <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
        <p class="text-xs text-gray-400 mt-1">Registered users</p>
    </div>
</div>

{{-- Chart + Order Status --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
    <div class="xl:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Revenue — Last 7 Days</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Orders by Status</h3>
        <div class="space-y-3">
            @php
                $statusColors = [
                    'pending'    => 'bg-yellow-400',
                    'processing' => 'bg-blue-400',
                    'shipped'    => 'bg-purple-400',
                    'delivered'  => 'bg-green-400',
                    'cancelled'  => 'bg-red-400',
                ];
            @endphp
            @foreach($ordersByStatus as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $statusColors[$status] ?? 'bg-gray-400' }}"></div>
                        <span class="text-sm text-gray-600 capitalize">{{ $status }}</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                </div>
            @endforeach
            @if($ordersByStatus->isEmpty())
                <p class="text-sm text-gray-400 text-center py-4">No orders yet</p>
            @endif
        </div>
    </div>
</div>

{{-- Recent Orders + Low Stock --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-gray-700">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Order</th>
                        <th class="px-5 py-3 text-left">Customer</th>
                        <th class="px-5 py-3 text-left">Total</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $order->shipping_name }}</td>
                            <td class="px-5 py-3 font-medium">৳{{ number_format($order->total) }}</td>
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
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Low Stock Alert</h3>
            <a href="{{ route('admin.inventory.index') }}" class="text-xs text-gray-400 hover:text-gray-700">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($lowStockProducts as $product)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($product->name, 25) }}</p>
                        <p class="text-xs text-gray-400">{{ $product->category->name ?? '' }}</p>
                    </div>
                    <span class="text-sm font-bold {{ $product->stock <= 2 ? 'text-red-500' : 'text-orange-500' }}">
                        {{ $product->stock }} left
                    </span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">
                    <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>
                    All products well stocked!
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Top Products --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Top Products by Revenue</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">#</th>
                <th class="px-5 py-3 text-left">Product</th>
                <th class="px-5 py-3 text-left">Units Sold</th>
                <th class="px-5 py-3 text-left">Revenue</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($topProducts as $i => $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400 font-medium">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $product->units }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-900">৳{{ number_format($product->revenue) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-gray-400">No sales data yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartDays) !!},
        datasets: [{
            label: 'Revenue (৳)',
            data: {!! json_encode($chartRevenues) !!},
            borderColor: '#111827',
            backgroundColor: 'rgba(17,24,39,0.06)',
            borderWidth: 2,
            pointBackgroundColor: '#111827',
            pointRadius: 4,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { callback: v => '৳' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush