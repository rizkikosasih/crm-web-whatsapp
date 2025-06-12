<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
  public function definition(): array
  {
    return [
      'name' => $this->faker->name(),
      'username' => $this->faker->unique()->userName(),
      'password' => Hash::make('password'), // Default password
      'email' => $this->faker->unique()->safeEmail(),
      'phone' => $this->faker->optional()->numerify('628############'),
      'email_verified_at' => now(),
      'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
      'address' => $this->faker->optional()->address(),
      'avatar' => null, // Atau generate URL dummy pakai faker()->imageUrl() jika perlu
      'last_login_at' => $this->faker->optional()->dateTimeBetween('-1 month'),
      'last_login_ip' => null,
      'is_active' => true,
    ];
  }
}
