@section('title', $title)

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <x-link.button-primary url="{{ url('customer/create') }}">
              <i class="fas fa-plus"></i> Tambah
            </x-link.button-primary>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
              <div class="col-auto">
                <x-form.input-group-select
                  label="Length"
                  name="perPage"
                  wire:model.live="perPage"
                  :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
                />
              </div>

              <div class="ml-auto">
                <div class="col-auto">
                  <x-form.input
                    name="search"
                    placeholder="Cari Pelanggan..."
                    wire:model.live.debounce.250ms="search"
                  />
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($items as $index => $item)
                    <tr>
                      <td class="text-center">{{ $index + $items->firstItem() }}</td>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->phone }}</td>
                      <td class="actions">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <x-link.icon-success customClass="tooltips" title="Send Whatsapp">
                            <i class="fab fa-whatsapp"></i>
                          </x-link.icon-success>

                          <x-link.icon-primary
                            url="{{ url('customer/update/' . $item->id) }}"
                            customClass="tooltips"
                            title="Ubah"
                            wire:navigate
                          >
                            <i class="fas fa-pencil"></i>
                          </x-link.icon-primary>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="{{ sizeof($tableHeader) }}" class="text-center">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            {{ $items->links('partials.pagination.bootstrap4') }}
          </div>
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>
