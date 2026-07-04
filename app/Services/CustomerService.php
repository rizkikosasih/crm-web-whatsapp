<?php

namespace App\Services;

use App\Repositories\Eloquent\CustomerRepository;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get paginated customer list.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->customerRepository->getPaginated($perPage, $search);
    }

    /**
     * Find a customer.
     */
    public function find(int $id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * Save customer record.
     */
    public function save(array $data, ?int $id = null)
    {
        // Validation check for uniqueness of phone number
        $existing = $this->customerRepository->findByPhone($data['phone']);
        if ($existing && $existing->id !== $id) {
            throw new \Exception('No Handphone sudah ada');
        }

        return $this->customerRepository->createOrUpdate($data, $id);
    }
}
