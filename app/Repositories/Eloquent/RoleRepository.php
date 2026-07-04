<?php

namespace App\Repositories\Eloquent;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    /**
     * Get paginated roles.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Role::when($search, function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find role by ID.
     */
    public function find(int $id)
    {
        return Role::findOrFail($id);
    }

    /**
     * Create or update a role.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return Role::updateOrCreate(
            ['id' => $id],
            ['name' => $data['name'], 'guard_name' => 'web'],
        );
    }

    /**
     * Get all permission records.
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }

    /**
     * Sync permissions to role.
     */
    public function syncPermissions(int $roleId, array $permissionNames)
    {
        $role = $this->find($roleId);
        $role->syncPermissions($permissionNames);
        return $role;
    }
}
