<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'category'])
            ->where('is_active', true);

        // Search
        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Category filter
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Price filter
        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        // Sort
        match($request->input('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function category(Category $category)
    {
        $products = Product::with(['primaryImage'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('shop.index', compact('products', 'categories', 'category'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        $product->load(['images', 'category']);

        $related = Product::with(['primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}