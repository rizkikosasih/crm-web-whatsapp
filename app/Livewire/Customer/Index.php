<?php

namespace App\Livewire\Customer;

use App\Services\CustomerService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Locked]
    public $title = 'Data Pelanggan';

    #[Locked]
    public $tableHeader = [
        ['name' => 'No'],
        ['name' => 'Nama'],
        ['name' => 'No Handphone'],
        ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
    ];

    public $isEdit = false;

    public $search = '';
    public $perPage = 10;

    public $customerId;
    #[Validate('required', message: 'Nama tidak boleh kosong')]
    public $name;
    #[Validate('required', message: 'No handphone tidak boleh kosong')]
    #[Validate('numeric', message: 'No handphone wajib angka')]
    public $phone;
    public $notes;
    public $originalPhone;

    public function save(CustomerService $customerService)
    {
        $this->validate();

        try {
            $customerService->save(
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'notes' => $this->notes,
                ],
                $this->customerId,
            );

            session()->flash('success', 'Data Pelanggan Berhasil Disimpan.');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->addError('phone', $e->getMessage());
        }
    }

    public function edit($id, CustomerService $customerService)
    {
        $customer = $customerService->find($id);

        $this->customerId = $id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->originalPhone = $customer->phone;
        $this->notes = $customer->notes;
        $this->isEdit = true;
        $this->dispatch('scrollToTop');
    }

    public function resetForm()
    {
        $this->reset(['customerId', 'name', 'phone', 'originalPhone', 'notes', 'isEdit']);
        $this->dispatch('clearError');
    }

    public function render(CustomerService $customerService)
    {
        $items = $customerService->getPaginated($this->perPage, $this->search);
        return view('livewire.customer.index', compact('items'));
    }
}
