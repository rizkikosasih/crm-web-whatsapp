<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    DB::beginTransaction();

    try {
      // Bersihkan folder gambar produk
      $folder = storage_path('app/public/images/products');
      if (File::exists($folder)) {
        File::cleanDirectory($folder);
      } else {
        File::makeDirectory($folder, 0755, true);
      }

      // Loop untuk membuat produk dengan SKU custom via factory
      foreach (range(1, 13) as $i) {
        Product::factory()->create([
          'sku' => 'SKU' . str_pad($i, 4, '0', STR_PAD_LEFT),
          'name' => 'Product ' . $i,
          'description' => 'Description for Product ' . $i,
        ]);
      }

      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
