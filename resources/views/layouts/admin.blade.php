<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – Madia Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-gray-300 flex flex-col flex-shrink-0">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="text-white font-bold text-lg tracking-tight">
                madia <span class="text-gray-400 text-xs font-normal">admin</span>
            </a>
        </div>

        <!-- Nav -->
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',        'icon' => 'fa-gauge-high',   'label' => 'Dashboard'],
                    ['route' => 'admin.orders.index',     'icon' => 'fa-bag-shopping',  'label' => 'Orders'],
                    ['route' => 'admin.products.index',   'icon' => 'fa-shirt',         'label' => 'Products'],
                    ['route' => 'admin.categories.index', 'icon' => 'fa-tags',          'label' => 'Categories'],
                    ['route' => 'admin.inventory.index',  'icon' => 'fa-boxes-stacked', 'label' => 'Inventory'],
                    ['route' => 'admin.coupons.index',    'icon' => 'fa-ticket',        'label' => 'Coupons'],
                    ['route' => 'admin.customers.index',  'icon' => 'fa-users',         'label' => 'Customers'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors
                          {{ request()->routeIs($item['route']) ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} w-4 text-center"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Bottom -->
        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-white mb-3">
                <i class="fas fa-store"></i> View Store
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-2 text-xs text-gray-400 hover:text-white w-full">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main area -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-3 text-sm flex-shrink-0">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-3 text-sm flex-shrink-0">
                {{ session('error') }}
            </div>
        @endif

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>