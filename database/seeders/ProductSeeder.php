<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    $folder = storage_path('app/public/images/products');
    File::cleanDirectory($folder);

    $products = [];

    for ($i = 1; $i <= 10; $i++) {
      $products[] = [
        'sku' => 'SKU' . str_pad($i, 4, '0', STR_PAD_LEFT),
        'name' => 'Product ' . $i,
        'description' => 'Description for Product ' . $i,
        'price' => mt_rand(100000, 1000000),
        'stock' => rand(1, 50),
        'created_at' => now(),
        'updated_at' => now(),
      ];
    }

    DB::table('products')->insert($products);
  }
}
