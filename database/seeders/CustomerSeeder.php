<?php

namespace Database\Seeders;

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

    $faker = Faker::create(env('APP_FAKE_LOCALE'));

    DB::table('customers')->insert([
      'name' => 'Rizki',
      'phone' => '6285777838862',
      'created_at' => now(),
    ]);

    DB::table('customers')->insert([
      'name' => 'Menda',
      'phone' => '6285291111124',
      'created_at' => now(),
    ]);

    foreach (range(1, 5) as $index) {
      DB::table('customers')->insert([
        'name' => $faker->name(),
        'phone' => $faker->regexify('628[1-9][0-9]{8,9}'),
        'created_at' => now(),
      ]);
    }

    DB::commit();
  }
}
