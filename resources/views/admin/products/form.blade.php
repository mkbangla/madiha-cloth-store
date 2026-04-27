@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')

@php
    $productSizes  = isset($product) ? (is_array($product->sizes)  ? $product->sizes  : json_decode($product->sizes  ?? '[]', true)) : [];
    $productColors = isset($product) ? (is_array($product->colors) ? $product->colors : json_decode($product->colors ?? '[]', true)) : [];
@endphp

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main Info --}}
        <div class="xl:col-span-2 space-y-5">

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Product Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               placeholder="e.g. Classic White Shirt" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               placeholder="Brief product summary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                                  placeholder="Detailed product description">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Pricing & Inventory</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Regular Price (৳) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               placeholder="0" step="0.01" required>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price (৳)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               placeholder="Leave empty if no sale" step="0.01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert At</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                               placeholder="Auto-generated if empty">
                    </div>
                </div>
            </div>

            {{-- Variants --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Sizes & Colors</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sizes</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['XS','S','M','L','XL','XXL','30','32','34','36','2Y','4Y','6Y','8Y','10Y'] as $size)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" class="sr-only peer"
                                           {{ in_array($size, $productSizes) ? 'checked' : '' }}>
                                    <span class="inline-block border border-gray-200 text-xs px-3 py-1.5 rounded-lg peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 hover:border-gray-400 transition-colors">
                                        {{ $size }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Colors</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Black','White','Red','Blue','Green','Yellow','Pink','Navy','Grey','Beige','Olive','Coral','Ivory','Charcoal'] as $color)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="colors[]" value="{{ $color }}" class="sr-only peer"
                                           {{ in_array($color, $productColors) ? 'checked' : '' }}>
                                    <span class="inline-block border border-gray-200 text-xs px-3 py-1.5 rounded-lg peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 hover:border-gray-400 transition-colors">
                                        {{ $color }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Product Images</h3>

                @if(isset($product) && $product->images->count())
                    <div class="grid grid-cols-5 gap-3 mb-4">
                        @foreach($product->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" class="w-full aspect-square object-cover rounded-lg border {{ $image->is_primary ? 'border-gray-900 border-2' : 'border-gray-200' }}">
                                @if($image->is_primary)
                                    <span class="absolute top-1 left-1 bg-gray-900 text-white text-xs px-1 rounded">Main</span>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100">
                                    @if(!$image->is_primary)
                                        <form action="{{ route('admin.products.images.primary', [$product, $image]) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="bg-white text-gray-800 text-xs px-2 py-1 rounded">Main</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.products.images.delete', [$product, $image]) }}" method="POST"
                                          onsubmit="return confirm('Delete image?')">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-500 text-white text-xs px-2 py-1 rounded">Del</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-gray-400 transition-colors">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500 mb-2">Click to upload or drag & drop</p>
                    <p class="text-xs text-gray-400">PNG, JPG up to 2MB each. Multiple allowed.</p>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="mt-3 text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor-pointer">
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Publish</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Active (visible in store)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Featured (show on homepage)</span>
                    </label>
                </div>
                <div class="mt-5 space-y-2">
                    <button type="submit"
                            class="w-full bg-gray-900 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        {{ isset($product) ? 'Update Product' : 'Create Product' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="block text-center text-sm text-gray-400 hover:text-gray-700 py-2">Cancel</a>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Category *</h3>
                <select name="category_id" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</form>
@endsection