<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class OrderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $folder = storage_path('app/public/images/proof_of_payments');
    File::cleanDirectory($folder);
  }
}
