<?php

namespace App\Repositories\Eloquent;

use App\Models\WhatsappApiSetting;

class WhatsappSettingsRepository
{
    /**
     * Get first settings record.
     */
    public function getFirst()
    {
        return WhatsappApiSetting::first();
    }

    /**
     * Create or update settings.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return WhatsappApiSetting::updateOrCreate(
            ['id' => $id],
            [
                'key' => $data['key'],
                'url' => $data['url'],
                'instance_name' => $data['instance_name'] ?? null,
            ],
        );
    }
}
