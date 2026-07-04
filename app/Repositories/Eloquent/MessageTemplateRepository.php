<?php

namespace App\Repositories\Eloquent;

use App\Models\MessageTemplate;

class MessageTemplateRepository
{
    /**
     * Find template by ID.
     */
    public function find(int $id)
    {
        return MessageTemplate::findOrFail($id);
    }

    /**
     * Find template by type.
     */
    public function findByType(string $type)
    {
        return MessageTemplate::where('type', $type)->first();
    }
}
