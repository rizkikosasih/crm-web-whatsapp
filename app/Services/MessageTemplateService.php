<?php

namespace App\Services;

use App\Repositories\Eloquent\MessageTemplateRepository;

class MessageTemplateService
{
    protected $messageTemplateRepository;

    public function __construct(MessageTemplateRepository $messageTemplateRepository)
    {
        $this->messageTemplateRepository = $messageTemplateRepository;
    }

    /**
     * Find template by ID.
     */
    public function find(int $id)
    {
        return $this->messageTemplateRepository->find($id);
    }

    /**
     * Find template by type.
     */
    public function findByType(string $type)
    {
        return $this->messageTemplateRepository->findByType($type);
    }
}
