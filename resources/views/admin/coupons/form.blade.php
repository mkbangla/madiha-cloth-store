@extends('layouts.admin')
@section('title', isset($coupon) ? 'Edit Coupon' : 'Add Coupon')
@section('page-title', isset($coupon) ? 'Edit Coupon' : 'Add Coupon')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 space-y-4">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 uppercase"
                           placeholder="e.g. SAVE20" required>
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed"      {{ old('type', $coupon->type ?? '') === 'fixed'      ? 'selected' : '' }}>Fixed Amount (৳)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Value *</label>
                    <input type="number" name="value" value="{{ old('value', $coupon->value ?? '') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                           placeholder="e.g. 20" step="0.01" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount (৳)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                           placeholder="0">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Discount (৳)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                           placeholder="Leave empty for no limit">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                           placeholder="Leave empty for unlimited">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded"
                       {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Active</span>
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                    {{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-400 hover:text-gray-700 py-2.5">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection