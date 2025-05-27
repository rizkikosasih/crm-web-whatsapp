<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhatsappSettingsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('whatsapp_settings')->insert([
      'key' => '-',
      'url' => 'https://panel.rapiwha.com/send_message.php',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}
