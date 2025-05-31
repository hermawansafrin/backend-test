<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Product example 1',
            'price' => 100000,
            'stock' => 100,
            'is_active' => 1,
        ]);

        Product::create([
            'name' => 'Product example 2',
            'price' => 50000,
            'stock' => 200,
            'is_active' => 1,
        ]);
    }
}
