<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Validate;

class Form extends Component
{
  public $customerId;

  #[Validate('required', message: 'Nama tidak boleh kosong')]
  public $name;

  #[Validate('required', message: 'No handphone tidak boleh kosong')]
  #[Validate('unique:customers,phone', message: 'No handphone sudah ada')]
  #[Validate('numeric', message: 'No handphone wajib angka')]
  public $phone;

  public $notes;

  public $title = 'Form Pelanggan';
  public $subtitle = 'Tambah';

  public function mount($id = null)
  {
    if ($id) {
      $customer = Customer::findOrFail($id);
      $this->customerId = $customer->id;
      $this->name = $customer->name;
      $this->phone = $customer->phone;
      $this->notes = $customer->notes;
      $this->subtitle = 'Edit';
    }
  }

  public function save()
  {
    $this->validate();

    Customer::updateOrCreate(
      ['id' => $this->customerId],
      ['name' => $this->name, 'phone' => $this->phone, 'notes' => $this->notes]
    );

    Session::flash('success', 'Data Pelanggan Berhasil Disimpan.');
    return $this->redirect(url('customer'), true);
  }

  public function render()
  {
    return view('livewire.customer.form');
  }
}
