<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])->latest();

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            $this->handleImageUpload($request->file('images'), $product, true);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $product->update($data);

        // Image upload on update
        if ($request->hasFile('images')) {
            $this->handleImageUpload($request->file('images'), $product);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate(['images.*' => 'required|image|max:2048']);
        $this->handleImageUpload($request->file('images'), $product);
        return back()->with('success', 'Images uploaded.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Set another image as primary if this was primary
        if ($image->is_primary && $product->images()->count()) {
            $product->images()->first()->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image deleted.');
    }

    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        return back()->with('success', 'Primary image updated.');
    }

    protected function handleImageUpload(array $images, Product $product, bool $firstAsPrimary = false): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($images as $i => $file) {
            $path = $file->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'is_primary' => $firstAsPrimary && $i === 0 && !$hasPrimary,
                'sort_order' => $product->images()->count(),
            ]);
        }
    }

    protected function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'               => 'required|string|max:200',
            'category_id'        => 'required|exists:categories,id',
            'description'        => 'nullable|string',
            'short_description'  => 'nullable|string|max:500',
            'price'              => 'required|numeric|min:0',
            'sale_price'         => 'nullable|numeric|min:0|lt:price',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'required|integer|min:1',
            'sku'                => 'nullable|string|unique:products,sku,' . $ignoreId,
            'sizes'              => 'nullable|array',
            'colors'             => 'nullable|array',
            'is_active'          => 'boolean',
            'is_featured'        => 'boolean',
        ]);
    }




}