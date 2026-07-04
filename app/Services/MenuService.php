<?php

namespace App\Services;

use App\Repositories\Eloquent\MenuRepository;

class MenuService
{
    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Get paginated menus.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->menuRepository->getPaginated($perPage, $search);
    }

    /**
     * Find menu by ID.
     */
    public function find(int $id)
    {
        return $this->menuRepository->find($id);
    }

    /**
     * Search parent menus.
     */
    public function searchParents(?string $parentSearch, int $limit = 3)
    {
        return $this->menuRepository->searchParents($parentSearch, $limit);
    }

    /**
     * Save menu.
     */
    public function save(array $data, ?int $id = null)
    {
        return $this->menuRepository->createOrUpdate($data, $id);
    }

    /**
     * Toggle active state.
     */
    public function toggleActive(int $id, bool $isActive)
    {
        return $this->menuRepository->toggleActive($id, $isActive);
    }

    /**
     * Delete menu.
     */
    public function delete(int $id)
    {
        return $this->menuRepository->delete($id);
    }
}
