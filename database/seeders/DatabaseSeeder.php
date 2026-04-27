<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@madia.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Sample customer
        User::create([
            'name'     => 'John Doe',
            'email'    => 'customer@madia.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Categories
        $categories = [
            ['name' => 'Men',       'slug' => 'men',       'description' => 'Men\'s clothing collection'],
            ['name' => 'Women',     'slug' => 'women',     'description' => 'Women\'s clothing collection'],
            ['name' => 'Kids',      'slug' => 'kids',      'description' => 'Kids\' clothing collection'],
            ['name' => 'Formal',    'slug' => 'formal',    'description' => 'Formal wear for all occasions'],
            ['name' => 'Casual',    'slug' => 'casual',    'description' => 'Everyday casual wear'],
            ['name' => 'Sale',      'slug' => 'sale',      'description' => 'Special discounted items'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Sample products
        $products = [
            ['category_id' => 1, 'name' => 'Classic White Shirt',      'slug' => 'classic-white-shirt',       'price' => 1299, 'stock' => 50, 'is_featured' => true,  'sizes' => ['S','M','L','XL'], 'colors' => ['White','Blue']],
            ['category_id' => 1, 'name' => 'Slim Fit Chinos',          'slug' => 'slim-fit-chinos',           'price' => 1799, 'stock' => 30, 'is_featured' => true,  'sizes' => ['30','32','34','36'], 'colors' => ['Beige','Navy','Olive']],
            ['category_id' => 1, 'name' => 'Casual Polo T-Shirt',      'slug' => 'casual-polo-t-shirt',       'price' =>  799, 'stock' => 80, 'is_featured' => false, 'sizes' => ['S','M','L','XL','XXL'], 'colors' => ['Black','White','Red']],
            ['category_id' => 2, 'name' => 'Floral Summer Dress',      'slug' => 'floral-summer-dress',       'price' => 1599, 'stock' => 40, 'is_featured' => true,  'sizes' => ['XS','S','M','L'], 'colors' => ['Pink','Yellow','Blue']],
            ['category_id' => 2, 'name' => 'Silk Evening Blouse',      'slug' => 'silk-evening-blouse',       'price' => 2199, 'stock' =>  3, 'is_featured' => true,  'sizes' => ['XS','S','M','L'], 'colors' => ['Ivory','Black']],
            ['category_id' => 2, 'name' => 'High-Waist Palazzo Pants', 'slug' => 'high-waist-palazzo-pants',  'price' => 1399, 'stock' => 25, 'is_featured' => false, 'sizes' => ['S','M','L'], 'colors' => ['Black','White','Coral']],
            ['category_id' => 3, 'name' => 'Kids Denim Jacket',        'slug' => 'kids-denim-jacket',         'price' =>  999, 'stock' => 20, 'is_featured' => false, 'sizes' => ['2Y','4Y','6Y','8Y','10Y'], 'colors' => ['Blue']],
            ['category_id' => 4, 'name' => 'Men\'s Suit Blazer',       'slug' => 'mens-suit-blazer',          'price' => 4999, 'stock' => 15, 'is_featured' => true,  'sizes' => ['S','M','L','XL'], 'colors' => ['Charcoal','Navy']],
            ['category_id' => 6, 'name' => 'Oversized Hoodie',         'slug' => 'oversized-hoodie',          'price' =>  599, 'sale_price' => 399, 'stock' => 60, 'is_featured' => false, 'sizes' => ['S','M','L','XL'], 'colors' => ['Grey','Black']],
            ['category_id' => 6, 'name' => 'Linen Kurta',              'slug' => 'linen-kurta',               'price' =>  899, 'sale_price' => 649, 'stock' =>  4, 'is_featured' => false, 'sizes' => ['M','L','XL','XXL'], 'colors' => ['White','Blue','Green']],
        ];

        foreach ($products as $p) {
            $sizes  = $p['sizes'];
            $colors = $p['colors'];
            unset($p['sizes'], $p['colors']);

            $p['sizes']       = json_encode($sizes);
            $p['colors']      = json_encode($colors);
            $p['description'] = 'Premium quality fabric. Comfortable and stylish for everyday wear. Machine washable.';
            $p['short_description'] = 'Premium quality, comfortable fit.';
            $p['sku'] = strtoupper(substr($p['slug'], 0, 8)) . rand(100, 999);

            Product::create($p);
        }

        // Coupons
        Coupon::create([
            'code'             => 'WELCOME20',
            'type'             => 'percentage',
            'value'            => 20,
            'min_order_amount' => 500,
            'max_discount'     => 300,
            'usage_limit'      => 100,
            'expires_at'       => now()->addMonths(3)->toDateString(),
            'is_active'        => true,
        ]);

        Coupon::create([
            'code'             => 'FLAT150',
            'type'             => 'fixed',
            'value'            => 150,
            'min_order_amount' => 1000,
            'expires_at'       => now()->addMonths(2)->toDateString(),
            'is_active'        => true,
        ]);
    }
}