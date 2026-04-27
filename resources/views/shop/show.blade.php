@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a> /
        <a href="{{ route('shop') }}" class="hover:text-gray-700">Shop</a> /
        <a href="{{ route('shop.category', $product->category) }}" class="hover:text-gray-700">{{ $product->category->name }}</a> /
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">

        {{-- Image Gallery --}}
        <div>
            {{-- Main image --}}
            <div class="bg-gray-100 rounded-2xl overflow-hidden aspect-square mb-3" id="main-image-wrap">
                <img id="main-image"
                     src="{{ $product->image_url }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $image)
                        <button onclick="document.getElementById('main-image').src='{{ $image->url }}'"
                                class="bg-gray-100 rounded-lg overflow-hidden aspect-square hover:ring-2 ring-gray-900 transition-all">
                            <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <p class="text-sm text-gray-400 mb-2">{{ $product->category->name }}</p>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="flex items-baseline gap-3 mb-6">
                <span class="text-2xl font-bold text-gray-900">৳{{ number_format($product->current_price) }}</span>
                @if($product->is_on_sale)
                    <span class="text-lg text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                    <span class="bg-red-100 text-red-600 text-sm font-medium px-2 py-0.5 rounded">
                        {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                    </span>
                @endif
            </div>

            {{-- Stock status --}}
            @if($product->is_out_of_stock)
                <p class="text-red-500 text-sm font-medium mb-4"><i class="fas fa-times-circle mr-1"></i>Out of Stock</p>
            @elseif($product->is_low_stock)
                <p class="text-orange-500 text-sm font-medium mb-4"><i class="fas fa-exclamation-circle mr-1"></i>Only {{ $product->stock }} left!</p>
            @else
                <p class="text-green-600 text-sm font-medium mb-4"><i class="fas fa-check-circle mr-1"></i>In Stock</p>
            @endif

            <p class="text-gray-600 leading-relaxed mb-8">{{ $product->short_description }}</p>

            {{-- Add to Cart Form --}}
            @if(!$product->is_out_of_stock)
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf

                    {{-- Size selection --}}
                    @if(!empty($product->sizes))
                        <div class="mb-5">
                            <p class="text-sm font-medium text-gray-700 mb-2">Size</p>
                            <div class="flex gap-2 flex-wrap">
                                @foreach($product->sizes as $size)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="size" value="{{ $size }}" class="sr-only size-radio" required>
                                        <span class="size-btn inline-block border border-gray-300 text-sm px-3 py-1.5 rounded-lg hover:border-gray-900 transition-colors cursor-pointer">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Color selection --}}
                    @if(!empty($product->colors))
                        <div class="mb-5">
                            <p class="text-sm font-medium text-gray-700 mb-2">Color</p>
                            <div class="flex gap-2 flex-wrap">
                                @foreach($product->colors as $color)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}" class="sr-only color-radio">
                                        <span class="color-btn inline-block border border-gray-300 text-sm px-3 py-1.5 rounded-lg hover:border-gray-900 transition-colors cursor-pointer">
                                            {{ $color }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quantity --}}
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-2">Quantity</p>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="adjustQty(-1)"
                                    class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50">−</button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-16 text-center border border-gray-300 rounded-lg py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <button type="button" onclick="adjustQty(1)"
                                    class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50">+</button>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-primary py-4 text-base rounded-xl">
                        <i class="fas fa-shopping-bag mr-2"></i>Add to Cart
                    </button>
                </form>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-400 py-4 rounded-xl text-base cursor-not-allowed">
                    Out of Stock
                </button>
            @endif

            {{-- SKU --}}
            @if($product->sku)
                <p class="text-xs text-gray-400 mt-4">SKU: {{ $product->sku }}</p>
            @endif

            {{-- Description --}}
            <div class="mt-8 border-t border-gray-100 pt-8">
                <h3 class="font-semibold text-gray-900 mb-3">Product Description</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $product->description }}</p>
            </div>
        </div>
    </div>

    {{-- Related products --}}
    @if($related->count())
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">You May Also Like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($related as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
function adjustQty(delta) {
    const input = document.getElementById('qty-input');
    const val   = parseInt(input.value) + delta;
    const max   = parseInt(input.max);
    if (val >= 1 && val <= max) input.value = val;
}

// Size selection
document.querySelectorAll('.size-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.style.backgroundColor = '';
            btn.style.color = '';
            btn.style.borderColor = '#D1D5DB';
        });
        this.nextElementSibling.style.backgroundColor = '#111827';
        this.nextElementSibling.style.color = '#ffffff';
        this.nextElementSibling.style.borderColor = '#111827';
    });
});

// Color selection
document.querySelectorAll('.color-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.style.backgroundColor = '';
            btn.style.color = '';
            btn.style.borderColor = '#D1D5DB';
        });
        this.nextElementSibling.style.backgroundColor = '#111827';
        this.nextElementSibling.style.color = '#ffffff';
        this.nextElementSibling.style.borderColor = '#111827';
    });
});

// Quantity
function adjustQty(delta) {
    const input = document.getElementById('qty-input');
    const val   = parseInt(input.value) + delta;
    const max   = parseInt(input.max);
    if (val >= 1 && val <= max) input.value = val;
}
</script>
@endpush