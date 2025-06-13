<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
  protected $model = Customer::class;

  public function definition(): array
  {
    return [
      'name' => $this->faker->name(),
      'phone' => $this->faker->unique()->numerify('628###########'), // Format nomor WA Indonesia
      'notes' => $this->faker->optional()->sentence(),
    ];
  }
}
