@extends('layouts.admin')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Customer</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Orders</th>
                <th class="px-5 py-3 text-left">Joined</th>
                <th class="px-5 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $customer->email }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $customer->orders_count }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-blue-500 hover:text-blue-700 text-xs font-medium">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No customers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
</div>
@endsection