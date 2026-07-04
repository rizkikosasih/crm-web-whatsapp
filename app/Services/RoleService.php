<?php

namespace App\Services;

use App\Repositories\Eloquent\RoleRepository;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get paginated roles.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->roleRepository->getPaginated($perPage, $search);
    }

    /**
     * Find role by ID.
     */
    public function find(int $id)
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Save role.
     */
    public function save(array $data, ?int $id = null)
    {
        return $this->roleRepository->createOrUpdate($data, $id);
    }

    /**
     * Get all Spatie permissions.
     */
    public function getAllPermissions()
    {
        return $this->roleRepository->getAllPermissions();
    }

    /**
     * Sync permissions to role.
     */
    public function syncPermissions(int $roleId, array $permissionNames)
    {
        return $this->roleRepository->syncPermissions($roleId, $permissionNames);
    }
}
