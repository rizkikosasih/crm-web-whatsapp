<?php

namespace App\Services;

use App\Repositories\Eloquent\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated users.
     */
    public function getPaginated(int $perPage, ?string $search, ?string $roleName)
    {
        return $this->userRepository->getPaginated($perPage, $search, $roleName);
    }

    /**
     * Find user by ID.
     */
    public function find(int $id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Save user.
     */
    public function save(array $data, ?int $id = null)
    {
        return $this->userRepository->createOrUpdate($data, $id);
    }

    /**
     * Toggle active state.
     */
    public function toggleActive(int $id, bool $isActive)
    {
        return $this->userRepository->toggleActive($id, $isActive);
    }
}
