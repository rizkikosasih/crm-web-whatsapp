<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuRoleFactory extends Factory
{
  public function definition(): array
  {
    return [
      'menu_id' => Menu::factory()->id, // atau pilih manual jika sudah ada menu
      'role_id' => Role::factory()->id, // atau pilih manual jika sudah ada role
    ];
  }
}
