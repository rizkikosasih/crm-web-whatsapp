<?php

namespace App\Repositories\Eloquent;

use App\Models\Campaign;

class CampaignRepository
{
    /**
     * Get paginated campaigns with search filter.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Campaign::with('creator')
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['title', 'message'], 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a campaign by ID.
     */
    public function find(int $id)
    {
        return Campaign::findOrFail($id);
    }

    /**
     * Create or update a campaign.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return Campaign::updateOrCreate(
            ['id' => $id],
            [
                'title' => $data['title'],
                'message' => $data['message'],
                'image' => $data['image'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'created_by' => $data['created_by'],
            ],
        );
    }
}
