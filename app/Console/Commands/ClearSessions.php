<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class ClearSessions extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'session:clear {minutes=60}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clear All Session Expired';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $minutes = $this->argument('minutes');
    $expirationTime = Carbon::now()->subMinutes($minutes);

    $deletedFilesCount = 0;

    $files = File::allFiles(storage_path('framework/sessions'));
    foreach ($files as $file) {
      if (Carbon::createFromTimestamp($file->getMTime())->lt($expirationTime)) {
        File::delete($file); // Menghapus file yang sudah kedaluwarsa
        $deletedFilesCount++;
      }
    }

    $this->info("{$deletedFilesCount} session files have been cleared.");
  }
}
