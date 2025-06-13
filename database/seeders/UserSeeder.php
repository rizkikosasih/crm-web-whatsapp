<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();

    // delete avatar user
    $folder = storage_path('app/public/images/avatars');
    File::cleanDirectory($folder);

    User::factory()->create([
      'name' => 'Testing Super Admin',
      'username' => 'testing',
      'email' => 'testing@example.com',
      'phone' => '628123456789',
      'password' => Hash::make('testing123'),
      'role_id' => 1,
    ]);

    User::factory()->create([
      'name' => 'Administrator',
      'username' => 'admin',
      'email' => 'admin@example.com',
      'phone' => '628987654321',
      'password' => Hash::make('admin123'),
      'role_id' => 2,
    ]);

    User::factory()->create([
      'name' => 'Pemilik Toko',
      'username' => 'owner',
      'email' => 'owner@example.com',
      'phone' => '628918273645',
      'password' => Hash::make('owner123'),
      'role_id' => 3,
    ]);

    DB::commit();
  }
}
