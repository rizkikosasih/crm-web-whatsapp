<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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

        // clean avatar user directory
        $folder = storage_path('app/public/images/avatars');
        if (File::exists($folder)) {
            File::cleanDirectory($folder);
        } else {
            File::makeDirectory($folder, 0755, true, true);
        }

        $superAdmin = User::create([
            'name' => 'Testing Super Admin',
            'username' => 'testing',
            'email' => 'testing@example.com',
            'phone' => '628123456789',
            'password' => Hash::make('testing123'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super-admin');

        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '628987654321',
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $owner = User::create([
            'name' => 'Pemilik Toko',
            'username' => 'owner',
            'email' => 'owner@example.com',
            'phone' => '628918273645',
            'password' => Hash::make('owner123'),
            'is_active' => true,
        ]);
        $owner->assignRole('owner');

        DB::commit();
    }
}
