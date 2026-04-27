@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
               class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 w-64">
        <select name="category_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
    </form>
    <a href="{{ route('admin.products.create') }}"
       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Product</th>
                <th class="px-5 py-3 text-left">Category</th>
                <th class="px-5 py-3 text-left">Price</th>
                <th class="px-5 py-3 text-left">Stock</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $product->category->name }}</td>
                    <td class="px-5 py-3">
                        <span class="font-medium">৳{{ number_format($product->current_price) }}</span>
                        @if($product->is_on_sale)
                            <span class="text-xs text-gray-400 line-through ml-1">৳{{ number_format($product->price) }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-medium {{ $product->stock == 0 ? 'text-red-500' : ($product->is_low_stock ? 'text-orange-500' : 'text-gray-700') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($product->is_featured)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 ml-1">Featured</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-blue-500 hover:text-blue-700 text-xs font-medium">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        No products found.
                        <a href="{{ route('admin.products.create') }}" class="text-gray-700 underline ml-1">Add one</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
</div>
@endsection