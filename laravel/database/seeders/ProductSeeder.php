<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios si no hay
        if (User::count() == 0) {
            User::factory(5)->create();
        }

        // Crear categorías si no hay
        if (Category::count() == 0) {
            Category::factory()->count(5)->create();
        }

        // Crear productos con imagen por defecto
        Product::factory(20)->create()->each(function ($product) {
            ProductImage::factory()->create([
                'product_id' => $product->id,
                'path' => 'images/default-product.png'
            ]);
        });
    }
}
