<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition(): array
  {
    return [
      'sku' => strtoupper('SKU-' . $this->faker->unique()->bothify('??###')),
      'name' => $this->faker->words(3, true),
      'description' => $this->faker->optional()->paragraph(),
      'price' => $this->faker->randomFloat(2, 10000, 500000), // Harga antara 10rb - 500rb
      'stock' => $this->faker->numberBetween(0, 100),
      'image' => null, // Jika belum ada implementasi upload
      'image_url' => null,
    ];
  }
}
