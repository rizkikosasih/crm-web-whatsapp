<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();

    Customer::create([
      'name' => 'Menda',
      'phone' => '6285291111124',
    ]);

    Customer::factory()->count(5)->create();

    DB::commit();
  }
}
