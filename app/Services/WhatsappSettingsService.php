<?php

namespace App\Services;

use App\Repositories\Eloquent\WhatsappSettingsRepository;

class WhatsappSettingsService
{
    protected $whatsappSettingsRepository;

    public function __construct(WhatsappSettingsRepository $whatsappSettingsRepository)
    {
        $this->whatsappSettingsRepository = $whatsappSettingsRepository;
    }

    /**
     * Get first settings.
     */
    public function getFirst()
    {
        return $this->whatsappSettingsRepository->getFirst();
    }

    /**
     * Save settings.
     */
    public function save(array $data, ?int $id = null)
    {
        return $this->whatsappSettingsRepository->createOrUpdate($data, $id);
    }
}
