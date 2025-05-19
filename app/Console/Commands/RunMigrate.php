<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class RunMigrate extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:migrate';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Run Migrations Step By Step Because Reference Key';

  protected function pathMigrations($fileName)
  {
    return 'database/migrations/' . $fileName . '_table.php';
  }

  /**
   * Execute the console command.
   */
  public function handle()
  {
    //Rollback All Migrations
    if (Schema::hasTable('migrations')) {
      Artisan::call('migrate:reset');
      DB::table('migrations')->truncate();
    }

    try {
      //Run Migrate Table
      Artisan::call('migrate:fresh');

      //Run Seeder
      Artisan::call('db:seed', ['--class' => 'MenuSeeder']);
      Artisan::call('db:seed', ['--class' => 'UserSeeder']);
      Artisan::call('db:seed', ['--class' => 'CustomerSeeder']);

      $folder = storage_path('app/images/products');

      File::cleanDirectory($folder);

      $this->info('Migrations Success');
    } catch (\Exception $e) {
      $this->info('Migrations Failed: ' . $e->getMessage());
    }
  }
}
