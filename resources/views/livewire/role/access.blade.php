@section('title', $title)

<section class="content">
  <div class="container-fluid" id="list">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $roleName }}</div>

            <x-card.tools minus="true" refresh="true" url="{{ url('setting/role') }}" />
          </div>

          <div class="card-body text-justify">
            <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
              <div class="col-auto">
                <x-form.input-group-select
                  prependText="Length"
                  name="perPage"
                  wire:model.live="perPage"
                  :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
                />
              </div>

              <div class="ml-auto">
                <div class="col-auto">
                  <x-form.input
                    name="search"
                    placeholder="Cari menu..."
                    wire:model.live.debounce.250ms="search"
                  />
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <x-overlay>
                <table class="table table-striped table-bordered">
                  <x-table.header :columns="$tableHeader" />

                  <tbody>
                    @forelse ($items as $index => $item)
                      <tr>
                        <td class="text-center">{{ $index + $items->firstItem() }}</td>
                        <td>{{ $item->name }}</td>
                        <td><i class="{{ $item->icon }}"></i> {{ $item->icon }}</td>
                        <td class="actions">
                          <div class="btn-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                              <input
                                type="checkbox"
                                name="switch"
                                id="{{ Str::slug($item->name) . $item->id }}"
                                class="custom-control-input"
                                wire:click="toggleMenuAccess({{$item->id}})"
                                {{ $item->is_assigned ? 'checked' : '' }}
                              />
                              <label class="custom-control-label" for="{{ Str::slug($item->name) . $item->id }}"></label>
                            </div>
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
              </x-overlay>
            </div>

            {{ $items->links('partials.pagination.bootstrap4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
