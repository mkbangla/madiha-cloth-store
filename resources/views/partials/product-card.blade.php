<div class="group">
    <a href="{{ route('product.show', $product) }}" class="block">
        <div class="relative bg-gray-100 rounded-xl overflow-hidden aspect-[3/4] mb-3">
            <img src="{{ $product->image_url }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

            {{-- Badges --}}
            <div class="absolute top-2 left-2 flex flex-col gap-1">
                @if($product->is_on_sale)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded font-medium">Sale</span>
                @endif
                @if($product->is_out_of_stock)
                    <span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded font-medium">Sold Out</span>
                @elseif($product->is_low_stock)
                    <span class="bg-orange-400 text-white text-xs px-2 py-0.5 rounded font-medium">Low Stock</span>
                @endif
            </div>

            {{-- Quick add button --}}
            @if(!$product->is_out_of_stock)
                <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gray-900 text-white text-sm py-3 font-medium hover:bg-gray-700 transition-colors">
                            Quick Add
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </a>

    <div>
        <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? '' }}</p>
        <a href="{{ route('product.show', $product) }}" class="text-sm font-medium text-gray-900 hover:text-gray-600 block leading-snug mb-1">
            {{ $product->name }}
        </a>
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-900">৳{{ number_format($product->current_price) }}</span>
            @if($product->is_on_sale)
                <span class="text-xs text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
            @endif
        </div>
    </div>
</div>