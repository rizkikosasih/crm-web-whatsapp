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

    /**
     * Get paginated templates.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->messageTemplateRepository->getPaginated($perPage, $search);
    }

    /**
     * Save message template.
     */
    public function save(array $data, ?int $id = null)
    {
        return $this->messageTemplateRepository->createOrUpdate($data, $id);
    }
}
