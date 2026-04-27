<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        $filter = $request->input('filter', 'all');
        if ($filter === 'low')  $query->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0);
        if ($filter === 'out')  $query->where('stock', 0);

        $products = $query->orderBy('stock')->paginate(30)->withQueryString();

        $outOfStockCount  = Product::where('stock', 0)->count();
        $lowStockCount    = Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0)->count();

        return view('admin.inventory.index', compact('products', 'filter', 'outOfStockCount', 'lowStockCount'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'required|integer|min:1',
        ]);

        $product->update([
            'stock'               => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
        ]);

        return back()->with('success', "Stock updated for '{$product->name}'.");
    }
}