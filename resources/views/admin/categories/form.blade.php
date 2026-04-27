@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Category' : 'Add Category')
@section('page-title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                       placeholder="e.g. Men, Women, Kids" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                          placeholder="Brief category description">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                           min="0">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                @if(isset($category) && $category->image)
                    <img src="{{ $category->image_url }}"
                         class="w-24 h-24 object-cover rounded-lg mb-3 border border-gray-200">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                    {{ isset($category) ? 'Update Category' : 'Create Category' }}
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-sm text-gray-400 hover:text-gray-700 py-2.5">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection