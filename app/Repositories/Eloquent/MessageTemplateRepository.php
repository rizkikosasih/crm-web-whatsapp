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

    /**
     * Get paginated message templates.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return MessageTemplate::when($search, function ($query) use ($search) {
            $query->whereAny(['title', 'body'], 'like', '%' . $search . '%');
        })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create or update message template.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return MessageTemplate::updateOrCreate(
            ['id' => $id],
            [
                'title' => $data['title'],
                'body' => $data['body'],
                'type' => $data['type'],
            ],
        );
    }
}
