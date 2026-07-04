<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Get paginated users with role relations.
     */
    public function getPaginated(int $perPage, ?string $search, ?string $roleName)
    {
        return User::with('roles')
            ->when($search, function ($q) use ($search) {
                $q->whereAny(['name', 'email', 'phone'], 'like', '%' . $search . '%');
            })
            ->when($roleName, function ($q) use ($roleName) {
                $q->role($roleName);
            })
            ->where('id', '!=', 1)
            ->where('id', '!=', auth()->id())
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find user by ID.
     */
    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Create or update a user and sync role.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        $userData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ];

        if (isset($data['password']) && $data['password']) {
            $userData['password'] = Hash::make($data['password']);
        } elseif (!$id) {
            $userData['password'] = Hash::make($data['username'] . '123');
        }

        if (isset($data['avatar']) && $data['avatar']) {
            $userData['avatar'] = $data['avatar'];
        }

        $user = User::updateOrCreate(['id' => $id], $userData);

        if (isset($data['role'])) {
            $user->syncRoles($data['role']);
        }

        return $user;
    }

    /**
     * Toggle active state.
     */
    public function toggleActive(int $id, bool $isActive)
    {
        $user = User::findOrFail($id);
        $user->is_active = $isActive;
        $user->save();
        return $user;
    }
}
