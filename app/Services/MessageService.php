<?php

namespace App\Services;

use App\Repositories\Eloquent\MessageRepository;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Get paginated messages.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->messageRepository->getPaginated($perPage, $search);
    }
}
