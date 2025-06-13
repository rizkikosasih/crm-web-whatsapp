<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'parent_id' => null, // default menu root (bisa diatur manual untuk sub-menu)
      'name' => ucfirst($this->faker->unique()->word()),
      'icon' => $this->faker
        ->optional()
        ->randomElement(['fas fa-home', 'fas fa-cog', 'fas fa-users']),
      'position' => $this->faker->numberBetween(1, 10),
      'route' => $this->faker->optional()->slug() ?: '#',
      'slug' => $this->faker->optional()->slug(),
      'is_active' => true,
      'is_sidebar' => true,
    ];
  }
}
