@extends('layouts.app')
@section('title', isset($category) ? $category->name : 'Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ isset($category) ? $category->name : 'Shop' }}</span>
    </nav>

    <div class="flex gap-8">

        {{-- Sidebar filters --}}
        <aside class="hidden md:block w-56 flex-shrink-0">
            <h3 class="font-semibold text-gray-900 mb-4">Categories</h3>
            <ul class="space-y-2 mb-8">
                <li>
                    <a href="{{ route('shop') }}"
                       class="text-sm {{ !isset($category) ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">
                        All Products
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('shop.category', $cat) }}"
                           class="text-sm {{ isset($category) && $category->id === $cat->id ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <h3 class="font-semibold text-gray-900 mb-4">Price Range</h3>
            <form action="{{ route('shop') }}" method="GET">
                @if(isset($category))
                    <input type="hidden" name="category" value="{{ $category->id }}">
                @endif
                <div class="flex gap-2 mb-3">
                    <input type="number" name="min_price" placeholder="Min ৳" value="{{ request('min_price') }}"
                           class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm">
                    <input type="number" name="max_price" placeholder="Max ৳" value="{{ request('max_price') }}"
                           class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm">
                </div>
                <button class="w-full bg-gray-900 text-white text-sm py-2 rounded hover:bg-gray-700 transition-colors">
                    Apply
                </button>
            </form>
        </aside>

        {{-- Products --}}
        <div class="flex-1">

            {{-- Toolbar --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
                <form action="{{ route('shop') }}" method="GET">
                    <select name="sort" onchange="this.form.submit()"
                            class="border border-gray-200 rounded px-3 py-2 text-sm text-gray-700 focus:outline-none">
                        <option value="latest"    {{ request('sort') === 'latest'     ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_desc"{{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="name"      {{ request('sort') === 'name'       ? 'selected' : '' }}>Name A–Z</option>
                    </select>
                </form>
            </div>

            {{-- Grid --}}
            @if($products->count())
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-10">{{ $products->links() }}</div>
            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-box-open text-4xl mb-4"></i>
                    <p>No products found.</p>
                    <a href="{{ route('shop') }}" class="text-gray-700 underline text-sm mt-2 block">Clear filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection