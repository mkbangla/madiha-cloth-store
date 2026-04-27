@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.create') }}"
       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Category</th>
                <th class="px-5 py-3 text-left">Slug</th>
                <th class="px-5 py-3 text-left">Products</th>
                <th class="px-5 py-3 text-left">Sort</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                                <img src="{{ $category->image_url }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400 text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-xs text-gray-400">{{ Str::limit($category->description, 40) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $category->products_count }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $category->sort_order }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="text-blue-500 hover:text-blue-700 text-xs font-medium">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        No categories found.
                        <a href="{{ route('admin.categories.create') }}" class="text-gray-700 underline ml-1">Add one</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
</div>
@endsection