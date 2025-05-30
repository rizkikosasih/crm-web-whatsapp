@section('title', $title)

<section class="content">
  <div class="container-fluid" id="row1">
    <p class="text-muted">
      Data di bawah ini adalah ringkasan pesanan dari
      <strong>{{ dateIndo(now()->subMonth()) }}</strong> sampai <strong>{{ dateIndo(now()) }}</strong>.
    </p>
    <div class="row">
      @foreach($items[0] as $item)
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
      @foreach ($items[1] as $chart)
        <div class="col-md-6 mb-4">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <div class="card-title">{{ $chart['title'] }}</div>
            </div>
            <div class="card-body text-justify">
              <canvas id="{{ $chart['id'] }}"></canvas>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>

@section('page-script')
  @vite(['resources/plugins/chart.js'])

  @verbatim
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const chartConfigs = @json($items[1]);

        chartConfigs.forEach(chart => {
          const ctx = document.getElementById(chart.id).getContext('2d');
          new Chart(ctx, chart.config);
        });
      });
    </script>
  @verbatim
@endsection
