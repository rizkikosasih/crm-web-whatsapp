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

    foreach (range(1, 100) as $index) {
      DB::table('customers')->insert([
        'name' => $faker->name(),
        'phone' => $faker->regexify('628[1-9][0-9]{8,9}'),
        'created_at' => now(),
      ]);
    }

    DB::commit();
  }
}
