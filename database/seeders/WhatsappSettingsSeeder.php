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
                'key' => config('services.evolution.key', 'crm_wa_secret_token'),
                'url' => config('services.evolution.url', 'http://localhost:8080'),
                'instance_name' => config('services.evolution.instance', 'crm-whatsapp'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
