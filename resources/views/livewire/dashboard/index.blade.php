@section('title', $title)

<section class="content">
  <div class="container-fluid" id="row1">
    <p class="text-muted">
      Data di bawah ini adalah ringkasan pesanan dari
      <strong>{{ dateIndo(now()->subMonth()) }}</strong> sampai <strong>{{ dateIndo(now()) }}</strong>.
    </p>
    <div class="row">
      @foreach($orderByStatus as $item)
        <div class="col-lg-3 col-6">
          {{-- small box --}}
          <div class="small-box bg-{{ $item['color'] }}">
            <div class="ribbon-wrapper">
              <div class="ribbon bg-{{ $item['colorRibbon'] }}">
                Pesanan
              </div>
            </div>
            <div class="inner">
              <h3>{{ $item['count'] }}</h3>

              <p>{{ $item['title'] }}</p>
            </div>
            <div class="icon">
              <i class="{{ $item['icon'] }}"></i>
            </div>
            <a href="{{ $item['url'] }}" class="small-box-footer" wire:navigate>Info Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="container-fluid" id="row2">
    <div class="row">
      @foreach ($charts as $chart)
        @if($chart['show'])
          <div class="col-md-6 mb-4">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <div class="card-title">{{ $chart['title'] }}</div>
                <x-card.tools minus="true"/>
              </div>
              <div class="card-body text-justify">
                <canvas id="{{ $chart['id'] }}"></canvas>
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>

  <div class="container-fluid" id="row3">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">Histori Pesan Keluar</div>

            <x-card.tools minus="true"/>
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
                    placeholder="Cari Pesan..."
                    wire:model.live.debounce.250ms="search"
                  />
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$messageHeader" />

                <tbody>
                  @forelse ($messages as $index => $item)
                    <tr>
                      <td class="text-center">{{ $index + $messages->firstItem() }}</td>
                      <td>{{ $item->customer->name }}</td>
                      <td>{{ $item->user->name }}</td>
                      <td>{!! nl2br(e($item->message)) !!}</td>
                      <td class="text-center">
                        @if($item->image)
                          <x-preview-image></x-preview-image>
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
                      <td colspan="{{ sizeof($messageHeader) }}" class="text-center">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            {{ $messages->links('partials.pagination.bootstrap4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@section('page-script')
  @vite(['resources/plugins/chart.js', 'resources/js/ekko-lightbox.js'])

  @verbatim
    <script>
      function initCharts() {
        const chartConfigs = @json($charts);
        chartConfigs.forEach(chart => {
          const selectorId = document.getElementById(chart.id);
          if (chart.show && selectorId) {
            const ctx = selectorId.getContext('2d');
            new Chart(ctx, chart.config);
          }
        });
      }

      // document.addEventListener('DOMContentLoaded', initCharts);
      document.addEventListener('livewire:navigated', initCharts);
    </script>
  @verbatim
@endsection
