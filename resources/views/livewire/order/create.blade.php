@section('title', $title)

@section('page-script')
  @vite(['resources/js/form.js'])
@endsection

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <x-link.button
              url="{{ url('transaksi/order') }}"
              color="danger"
              size="sm"
            >
              <i class="fas fa-arrow-left"></i> Kembali
            </x-link.button>
          </div>

          <div class="card-body text-justify">
            <x-dropdown-search
              label="Pelanggan"
              name="customer_id"
              placeholder="Cari pelanggan..."
              horizontal="true"
              :items="$customers"
              :views="['name', 'phone']"
              :searching="$customerSearch"
              :selectedName="$selectedCustomerName"
              searchModel="customerSearch"
              selectedNameModel="selectedCustomerName"
              onSelect="selectCustomer"
            />

            <hr/>

            <div class="form-group row">
              <div class="col-md-6">
                <x-dropdown-search
                  label="Produk"
                  name="product_id"
                  placeholder="Cari produk..."
                  :items="$products"
                  :searching="$productSearch"
                  :selectedName="$selectedProductName"
                  searchModel="productSearch"
                  selectedNameModel="selectedProductName"
                  onSelect="selectProduct"
                />
              </div>
              <div class="col-md-3">
                <label>Jumlah</label>
                <input type="number" wire:model="quantity" class="form-control" min="1">
              </div>
              <div class="col-md-3">
                <label>&nbsp;</label>
                <x-button.custom class="btn-block" color="primary" wire:click="addProduct">
                  Tambah
                </x-button.custom>
              </div>
            </div>

            @if($orderItems)
              <hr/>

              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="text-center">
                    <tr>
                      <th>Produk</th>
                      <th>Harga</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                      <th><i class="fas fa-cogs"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($orderItems as $index => $item)
                      <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-end">{{ rupiah($item['price']) }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-end">{{ rupiah($item['price'] * $item['quantity']) }}</td>
                        <td class="actions">
                          <div class="btn-group">
                            <x-button.custom
                              class="tooltips"
                              color="danger"
                              size="sm"
                              wire:click="removeItem({{ $index }})"
                              title="Hapus Item"
                            >
                              <i class="fas fa-remove"></i>
                            </x-button.custom>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="text-end"><h5>Total</h5></td>
                      <td class="text-end">
                        <h5 class="fw-bold">{{ rupiah(collect($orderItems)->sum(fn ($i) => $i['price'] * $i['quantity'])) }}</h5>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <x-button.custom wire:click="save" color="success">Simpan Order</x-button.custom>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

