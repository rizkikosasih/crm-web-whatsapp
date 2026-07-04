<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;

class MessageRepository
{
    /**
     * Get paginated messages.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Message::with(['customer', 'user'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('customer', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($perPage);
    }
}
