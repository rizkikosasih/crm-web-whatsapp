@section('title', $title)

@section('page-script')
  @vite(['resources/js/ekko-lightbox.js'])
@endsection

<section class="content">
  <div class="container-fluid" id="list">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">Histori Pesan Keluar</div>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" wire:click="$refresh">
                <i class="fas fa-refresh"></i>
              </button>
            </div>
          </div>

          <div class="card-body text-justify">
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
                    placeholder="Cari Pesan..."
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
                      <td>{{ $item->customer->name }}</td>
                      <td>{{ $item->user->name }}</td>
                      <td>{!! nl2br(e($item->message)) !!}</td>
                      <td class="text-center">
                        @if($item->image)
                          <a
                            href="{{ imageUri($item->image ?? 'images/no-image.svg') }}"
                            data-toggle="lightbox"
                            class="tooltips"
                            title="Perbesar"
                          >
                            <img
                              class="img-rounded"
                              style="width:30px;height:auto"
                              src="{{ imageUri($item->image ?? 'images/no-image.svg') }}"
                            />
                          </a>
                        @else
                          -
                        @endif
                      </td>
                      <td class="text-center">
                        <div>{{ dateIndo($item->sent_at) }}</div>
                        <div>{{ timeIndo($item->sent_at) }}</div>
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
