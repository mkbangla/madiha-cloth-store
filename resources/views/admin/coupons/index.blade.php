@extends('layouts.admin')
@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.coupons.create') }}"
       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Coupon
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Code</th>
                <th class="px-5 py-3 text-left">Type</th>
                <th class="px-5 py-3 text-left">Value</th>
                <th class="px-5 py-3 text-left">Min Order</th>
                <th class="px-5 py-3 text-left">Used</th>
                <th class="px-5 py-3 text-left">Expires</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <span class="font-mono font-bold text-gray-900">{{ $coupon->code }}</span>
                    </td>
                    <td class="px-5 py-3 capitalize text-gray-600">{{ $coupon->type }}</td>
                    <td class="px-5 py-3 font-medium">
                        {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '৳' . number_format($coupon->value) }}
                    </td>
                    <td class="px-5 py-3 text-gray-600">৳{{ number_format($coupon->min_order_amount) }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 text-xs">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-5 py-3">
                        @if(!$coupon->is_active)
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">Inactive</span>
                        @elseif($coupon->is_expired)
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Expired</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Active</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 flex gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-500 hover:text-blue-700 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No coupons yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $coupons->links() }}</div>
</div>
@endsection