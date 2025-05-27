@section('title', $title)

@section('page-style')
  @vite(['resources/js/ekko-lightbox.js'])
@endsection

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <x-link.button-danger customClass="btn-sm" url="{{ url('transaksi/order') }}" wire:navigate>
              <i class="fas fa-arrow-left"></i> Kembali
            </x-link.button-danger>

            <x-card.tools refresh="true"/>
          </div>

          <div class="card-body text-justify">
            <table class="order-detail">
              <tr>
                <td>ID Pemesanan</td>
                <td style="width: 10%;">:</td>
                <td>#{{ $order->id }}</td>
              </tr>
              <tr>
                <td>Pelanggan</td>
                <td>:</td>
                <td>{{ $order->customer->name }}</td>
              </tr>
              <tr>
                <td>Pesanan Dibuat Oleh</td>
                <td>:</td>
                <td>{{ $order->user->name }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                  <x-button.default customClass="btn-sm btn-{{ $colorStatus[$order->status] }}">
                    {{ $statusList[$order->status] }}
                  </x-button.default>
                </td>
              </tr>
              @if ($order->status > 0 && $order->status < 4)
                <tr>
                  <td>Bukti Bayar</td>
                  <td>:</td>
                  <td>
                    <a
                      href="{{ imageUri($order->proof_of_payment ?? 'images/no-image.svg') }}"
                      data-toggle="lightbox"
                      class="tooltips"
                      title="Perbesar"
                    >
                      <img
                        class="img-rounded"
                        style="width:30px;height:auto"
                        src="{{ imageUri($order->proof_of_payment ?? 'images/no-image.svg') }}"
                      />
                    </a>
                  </td>
                </tr>
              @endif
            </table>

            @if(!in_array($order->status, [3,4]))
              <div class="d-flex flex-wrap my-3">
                <select wire:model.live="selectedStatus" class="form-control form-control-sm col-sm-3">
                  @foreach($this->availableStatusOptions() as $status)
                    <option value="{{ $status }}">{{ $statusList[$status] }}</option>
                  @endforeach
                </select>

                <div class="col-sm-3">
                  <button class="btn btn-sm btn-primary btn-block" wire:click="updateStatus">Update</button>
                </div>
              </div>

              @if($selectedStatus == 1 && !$order->proof_of_payment)
                <div class="d-flex">
                  <div class="col-sm-6 p-0">
                    <x-form.image
                      id="proof_of_payment"
                      name="proof_of_payment"
                      label="Bukti Bayar"
                      path="{{ $proof_of_payment ?? null }}"
                      :preview="$proof_of_payment"
                      customClass="form-control-sm"
                      wire:model.defer="proof_of_payment"
                    />
                  </div>
                </div>
              @endif
            @endif


            <hr/>
            <h6>Produk</h6>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center">
                  <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->orderItems as $index => $item)
                    <tr>
                      <td>{{ $item->product->name }}</td>
                      <td class="text-end">{{ rupiah($item->price) }}</td>
                      <td class="text-center">{{ $item->quantity }}</td>
                      <td class="text-end">{{ rupiah($item->price * $item->quantity) }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="text-end"><h5>Total</h5></td>
                    <td class="text-end">
                      <h5 class="fw-bold">{{ rupiah($order->total_amount) }}</h5>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <!-- /. card body -->
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>
