@extends('layouts.app')
@section('title', 'Welcome')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 md:py-28">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            {{-- Text --}}
            <div class="order-2 md:order-1">
                <span class="inline-block bg-white/10 text-white text-xs font-medium tracking-widest uppercase px-4 py-2 rounded-full mb-6">
                    ✨ New Collection 2024
                </span>
                <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6">
                    Wear What<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-200 to-white">
                        Feels Right
                    </span>
                </h1>
                <p class="text-gray-300 text-lg mb-10 leading-relaxed max-w-md">
                    Premium clothing for every occasion. Discover our latest collection of everyday essentials and statement pieces.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="{{ route('shop') }}"
                       class="bg-white text-gray-900 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform">
                        Shop Now →
                    </a>
                    <a href="{{ route('shop.category', 'sale') }}"
                       class="border border-white/30 text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/10 transition-all duration-200 backdrop-blur-sm">
                        View Sale
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex gap-8 mt-12 pt-8 border-t border-white/10">
                    <div>
                        <p class="text-2xl font-bold text-white">500+</p>
                        <p class="text-gray-400 text-sm">Products</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">10k+</p>
                        <p class="text-gray-400 text-sm">Customers</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">4.9★</p>
                        <p class="text-gray-400 text-sm">Rating</p>
                    </div>
                </div>
            </div>

            {{-- Hero Image --}}
            {{-- Hero Image --}}
<div class="order-1 md:order-2 relative">
    <div class="relative bg-gradient-to-br from-gray-700 to-gray-600 rounded-3xl overflow-hidden aspect-[4/5] max-w-sm mx-auto shadow-2xl">

        {{-- Product image --}}
        @if($heroProduct && $heroProduct->primaryImage)
            <img src="{{ $heroProduct->primaryImage->url }}"
                 alt="{{ $heroProduct->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="fas fa-shirt text-6xl mb-4 opacity-30"></i>
                    <p class="text-sm opacity-50">Upload a product image</p>
                </div>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-4 right-4 bg-white text-gray-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
            New Arrival
        </div>

        @if($heroProduct)
            <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur rounded-xl px-4 py-3 shadow-xl">
                <p class="text-xs text-gray-500">{{ $heroProduct->name }}</p>
                <p class="text-gray-900 font-bold text-lg">৳{{ number_format($heroProduct->current_price) }}</p>
            </div>
        @else
            <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur rounded-xl px-4 py-3 shadow-xl">
                <p class="text-xs text-gray-500">Starting from</p>
                <p class="text-gray-900 font-bold text-lg">৳799</p>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</section>

{{-- Features Bar --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-truck text-gray-700 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Free Delivery</p>
                    <p class="text-xs text-gray-400">Orders over ৳1500</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-rotate-left text-gray-700 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Easy Returns</p>
                    <p class="text-xs text-gray-400">7 day return policy</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shield-halved text-gray-700 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Secure Payment</p>
                    <p class="text-xs text-gray-400">100% protected</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-headset text-gray-700 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">24/7 Support</p>
                    <p class="text-xs text-gray-400">Always here to help</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Shop by Category</h2>
        <p class="text-gray-400">Find exactly what you're looking for</p>
    </div>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('shop.category', $category) }}"
               class="group relative bg-gray-50 hover:bg-gray-900 rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="w-14 h-14 bg-white group-hover:bg-white/10 rounded-2xl mx-auto mb-3 flex items-center justify-center shadow-sm transition-colors">
                    @if($category->image)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-10 h-10 object-cover rounded-xl">
                    @else
                        <i class="fas fa-shirt text-gray-500 group-hover:text-white text-xl transition-colors"></i>
                    @endif
                </div>
                <p class="text-xs font-semibold text-gray-700 group-hover:text-white transition-colors">{{ $category->name }}</p>
            </a>
        @endforeach
    </div>
</section>

{{-- Featured Products --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
            <p class="text-gray-400 text-sm mt-1">Handpicked just for you</p>
        </div>
        <a href="{{ route('shop') }}"
           class="text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-200 px-4 py-2 rounded-lg hover:border-gray-400 transition-all">
            View All →
        </a>
    </div>

    @if($featuredProducts->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @else
        <p class="text-gray-400 text-center py-10">No featured products yet.</p>
    @endif
</section>

{{-- Promo Banner --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="relative bg-gradient-to-r from-gray-900 to-gray-700 rounded-3xl overflow-hidden px-8 md:px-16 py-14 text-white">
        {{-- Background decoration --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2"></div>

        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <span class="inline-block bg-white/10 text-white text-xs font-medium px-3 py-1 rounded-full mb-4">
                    Limited Time Offer
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
                    Get 20% Off<br>Your First Order
                </h2>
                <p class="text-gray-300 mb-6">
                    Use code at checkout and save on your first purchase.
                </p>
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="bg-white/10 backdrop-blur border border-white/20 px-5 py-3 rounded-xl">
                        <span class="font-mono font-bold text-xl tracking-widest">WELCOME20</span>
                    </div>
                    <a href="{{ route('shop') }}"
                       class="bg-white text-gray-900 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                        Shop Now
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-end">
                <div class="text-center">
                    <div class="text-8xl font-black text-white/10 leading-none">20%</div>
                    <div class="text-white/30 text-sm font-medium -mt-2">DISCOUNT</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- New Arrivals --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-20">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">New Arrivals</h2>
            <p class="text-gray-400 text-sm mt-1">Fresh styles just landed</p>
        </div>
        <a href="{{ route('shop') }}?sort=latest"
           class="text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-200 px-4 py-2 rounded-lg hover:border-gray-400 transition-all">
            View All →
        </a>
    </div>

    @if($newArrivals->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($newArrivals as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @else
        <p class="text-gray-400 text-center py-10">No products yet.</p>
    @endif
</section>

@endsection