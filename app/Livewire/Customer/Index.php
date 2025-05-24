<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
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

  public function save()
  {
    $this->validate();

    if ($this->phone !== $this->originalPhone) {
      $rules['image'] = 'unique:customers,phone';
      $messages['image.unique'] = 'No Handphone sudah ada';
      $this->validate($rules, $messages);
    }

    Customer::updateOrCreate(
      ['id' => $this->customerId],
      ['name' => $this->name, 'phone' => $this->phone, 'notes' => $this->notes]
    );

    session()->flash('success', 'Data Pelanggan Berhasil Disimpan.');
    $this->resetForm();
  }

  public function edit($id)
  {
    $customer = Customer::findOrFail($id);

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

  public function render()
  {
    $items = Customer::when($this->search, function ($query) {
      $query->whereAny(['name', 'phone'], 'like', '%' . $this->search . '%');
    })
      ->latest()
      ->paginate($this->perPage);
    return view('livewire.customer.index', compact('items'));
  }
}
