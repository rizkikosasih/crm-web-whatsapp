<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();

    $disk = Storage::disk('public');
    $folder = 'images/avatars';
    if ($disk->exists($folder)) {
      $disk->deleteDirectory($folder);
    }

    DB::table('users')->insert([
      'name' => 'Rizki Kosasih',
      'username' => 'rizki',
      'email' => 'rizki@example.com',
      'phone' => '08123456789',
      'password' => Hash::make('rizki123'),
      'created_at' => Carbon::now()->subDays(7),
      'role_id' => 1,
    ]);

    DB::table('users')->insert([
      'name' => 'Administrator',
      'username' => 'admin',
      'email' => 'admin@example.com',
      'phone' => '08987654321',
      'password' => Hash::make('admin123'),
      'created_at' => Carbon::now()->subDays(10),
      'role_id' => 2,
    ]);

    DB::commit();
  }
}
