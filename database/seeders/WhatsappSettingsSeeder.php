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
        DB::table('whatsapp_settings')->updateOrInsert(
            ['id' => 1],
            [
                'key' => env('EVOLUTION_API_KEY', 'crm_wa_secret_token'),
                'url' => env('EVOLUTION_API_URL', 'http://localhost:8080'),
                'instance_name' => env('EVOLUTION_INSTANCE_NAME', 'crm-whatsapp'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
