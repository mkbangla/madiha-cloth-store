<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminInventoryController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category:slug}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/shop/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CouponController::class, 'apply'])->name('coupon.apply');
Route::delete('/cart/coupon', [CouponController::class, 'remove'])->name('coupon.remove');

// Checkout (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Customer account
Route::middleware(['auth'])->group(function () {
    Route::get('/account', [OrderController::class, 'index'])->name('account.index');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('account.order.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');



    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/images', [AdminProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::patch('products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Orders
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Inventory
    Route::get('inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::patch('inventory/{product}', [AdminInventoryController::class, 'update'])->name('inventory.update');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Customers
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
});



// এটা admin group এর বাইরে, আলাদা রাখুন
Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';