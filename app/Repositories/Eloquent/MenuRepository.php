<?php

namespace App\Repositories\Eloquent;

use App\Models\Menu;
use Illuminate\Support\Str;

class MenuRepository
{
    /**
     * Get paginated menus.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Menu::with('parent')
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['name', 'slug', 'route'], 'like', '%' . $search . '%');
            })
            ->orderByRaw('COALESCE(parent_id, 0) ASC')
            ->orderBy('position')
            ->paginate($perPage);
    }

    /**
     * Find menu by ID with parent relation.
     */
    public function find(int $id)
    {
        return Menu::with('parent')->findOrFail($id);
    }

    /**
     * Search parent menus.
     */
    public function searchParents(?string $parentSearch, int $limit = 3)
    {
        return Menu::when($parentSearch, function ($query) use ($parentSearch) {
            $query->where('name', 'like', '%' . $parentSearch . '%');
        })
            ->limit($limit)
            ->get();
    }

    /**
     * Create or update a menu record.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return Menu::updateOrCreate(
            ['id' => $id],
            [
                'name' => $data['name'],
                'parent_id' => $data['parent_id'] ?? null,
                'route' => $data['route'],
                'slug' => Str::slug($data['slug'] ?? $data['name']),
                'position' => $data['position'],
                'icon' => $data['icon'] ?? null,
                'permission' => $data['permission'] ?? null,
            ],
        );
    }

    /**
     * Toggle active state.
     */
    public function toggleActive(int $id, bool $isActive)
    {
        $menu = Menu::findOrFail($id);
        $menu->is_active = $isActive;
        $menu->save();
        return $menu;
    }

    /**
     * Delete a menu by ID.
     */
    public function delete(int $id)
    {
        return Menu::where('id', $id)->delete();
    }
}
