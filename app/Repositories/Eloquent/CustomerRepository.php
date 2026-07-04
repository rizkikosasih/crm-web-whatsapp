<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;

class CustomerRepository
{
    /**
     * Get paginated customers with search filter.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Customer::when($search, function ($query) use ($search) {
            $query->whereAny(['name', 'phone'], 'like', '%' . $search . '%');
        })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a customer by ID.
     */
    public function find(int $id)
    {
        return Customer::findOrFail($id);
    }

    /**
     * Create or update a customer.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return Customer::updateOrCreate(
            ['id' => $id],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'notes' => $data['notes'] ?? null,
            ],
        );
    }

    /**
     * Find a customer by phone number.
     */
    public function findByPhone(string $phone)
    {
        return Customer::where('phone', $phone)->first();
    }

    /**
     * Get all customers.
     */
    public function all()
    {
        return Customer::all();
    }
}
