@extends('layouts.admin')
@section('title', 'Inventory')
@section('page-title', 'Inventory Management')

@section('content')

<div class="flex gap-3 mb-6">
    <a href="{{ route('admin.inventory.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'all' ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        All Products
    </a>
    <a href="{{ route('admin.inventory.index') }}?filter=low"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'low' ? 'bg-orange-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        Low Stock <span class="ml-1 bg-orange-100 text-orange-700 px-1.5 rounded-full text-xs">{{ $lowStockCount }}</span>
    </a>
    <a href="{{ route('admin.inventory.index') }}?filter=out"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'out' ? 'bg-red-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        Out of Stock <span class="ml-1 bg-red-100 text-red-700 px-1.5 rounded-full text-xs">{{ $outOfStockCount }}</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Product</th>
                <th class="px-5 py-3 text-left">Category</th>
                <th class="px-5 py-3 text-left">Current Stock</th>
                <th class="px-5 py-3 text-left">Alert At</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Update</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-5 py-3">
                        <span class="font-bold text-lg {{ $product->stock == 0 ? 'text-red-500' : ($product->is_low_stock ? 'text-orange-500' : 'text-gray-800') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $product->low_stock_threshold }}</td>
                    <td class="px-5 py-3">
                        @if($product->stock == 0)
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Out of Stock</span>
                        @elseif($product->is_low_stock)
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Low Stock</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">In Stock</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <form action="{{ route('admin.inventory.update', $product) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                                   class="w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <input type="hidden" name="low_stock_threshold" value="{{ $product->low_stock_threshold }}">
                            <button class="bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-gray-700 transition-colors">
                                Save
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $products->links() }}</div>
</div>
@endsection