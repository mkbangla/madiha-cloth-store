<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Madia Cloth Store') – Premium Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .btn-primary { @apply bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 text-sm font-medium; }
        .btn-outline  { @apply border border-gray-900 text-gray-900 px-6 py-3 rounded-lg hover:bg-gray-900 hover:text-white transition-all duration-200 text-sm font-medium; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased">

<!-- Top bar -->
<div class="bg-gray-900 text-white text-xs text-center py-2 px-4 hidden sm:block">
    Free delivery on orders over ৳1500 &nbsp;|&nbsp; Use code <strong>WELCOME20</strong> for 20% off
</div>

<!-- Navigation -->
<nav class="border-b border-gray-100 bg-white sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-1">
                <span class="text-xl font-bold tracking-tight text-gray-900">Madiha</span>
                <span class="text-xs font-medium text-gray-400 tracking-widest uppercase hidden sm:block">cloth store</span>
            </a>

            <!-- Desktop Nav links -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Shop</a>
                @foreach(\App\Models\Category::where('is_active', true)->take(4)->get() as $cat)
                    <a href="{{ route('shop.category', $cat) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">{{ $cat->name }}</a>
                @endforeach
            </div>

            <!-- Icons -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('shop') }}" class="text-gray-500 hover:text-gray-900 p-1">
                    <i class="fas fa-search text-sm"></i>
                </a>

                @auth
                    <a href="{{ route('account.index') }}" class="text-gray-500 hover:text-gray-900 p-1 hidden sm:block">
                        <i class="fas fa-user text-sm"></i>
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-900 text-xs font-medium border border-gray-200 px-2 py-1 rounded hidden sm:block">
                            Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 p-1">
                        <i class="fas fa-user text-sm"></i>
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-gray-900 p-1">
                    <i class="fas fa-shopping-bag text-sm"></i>
                    @php 
                        try {
                            $cartCount = app(\App\Services\CartService::class)->count(); 
                        } catch(\Exception $e) {
                            $cartCount = 0;
                        }
                    @endphp
                    
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-gray-900 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center leading-none">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- Mobile menu button -->
                <button onclick="toggleMenu()" class="md:hidden text-gray-500 hover:text-gray-900 p-1">
                    <i class="fas fa-bars text-sm" id="menu-icon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('shop') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Shop</a>
            @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                <a href="{{ route('shop.category', $cat) }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">{{ $cat->name }}</a>
            @endforeach
            <div class="border-t border-gray-100 pt-2 mt-2">
                @auth
                    <a href="{{ route('account.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">My Account</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-gray-50 rounded-lg">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Flash messages -->
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 sm:px-6 py-3 text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 sm:px-6 py-3 text-sm">
        {{ session('error') }}
    </div>
@endif

<!-- Main content -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="col-span-2 md:col-span-1">
            <h3 class="text-white font-bold text-xl mb-3">madia</h3>
            <p class="text-sm leading-relaxed text-gray-400">Premium clothing store offering quality fashion for everyone. Based in Dhaka, Bangladesh.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Shop</h4>
            <ul class="space-y-2 text-sm">
                @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                    <li><a href="{{ route('shop.category', $cat) }}" class="hover:text-white transition-colors text-gray-400">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Account</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Register</a></li>
                <li><a href="{{ route('account.index') }}" class="hover:text-white">My Orders</a></li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Contact</h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><i class="fas fa-map-marker-alt mr-2"></i>Dhaka, Bangladesh</li>
                <li><i class="fas fa-phone mr-2"></i>+880 1700-000000</li>
                <li><i class="fas fa-envelope mr-2"></i>hello@madia.com</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-800 text-center py-4 text-xs text-gray-500">
        © {{ date('Y') }} Madia Cloth Store. All rights reserved.
    </div>
</footer>

<script>
function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    menu.classList.toggle('hidden');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
}
</script>

@stack('scripts')
</body>
</html>