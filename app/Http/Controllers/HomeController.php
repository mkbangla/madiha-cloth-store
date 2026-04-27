<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
{
    $featuredProducts = Product::with(['primaryImage', 'category'])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->latest()
        ->take(8)
        ->get();

    $categories = Category::where('is_active', true)
        ->orderBy('sort_order')
        ->take(6)
        ->get();

    $newArrivals = Product::with(['primaryImage'])
        ->where('is_active', true)
        ->latest()
        ->take(4)
        ->get();

    // Hero product — প্রথম featured product
    $heroProduct = Product::with(['primaryImage'])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->latest()
        ->first();

    return view('home', compact(
        'featuredProducts', 'categories', 'newArrivals', 'heroProduct'
    ));
}
}