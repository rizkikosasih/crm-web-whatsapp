@if ($paginator->hasPages())
  <nav class="d-flex align-items-center justify-content-between">
    <div class="d-flex justify-content-between flex-fill d-md-none">
      <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
              <span aria-hidden="true">&lsaquo;</span>
          </li>
        @else
          <li>
            <a wire:click="gotoPage({{ $paginator->currentPage() - 1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
          </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <li>
            <a wire:click="gotoPage({{ $paginator->currentPage() + 1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
          </li>
        @else
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span aria-hidden="true">&rsaquo;</span>
          </li>
        @endif
      </ul>
    </div>

    <div class="d-none flex-md-fill d-md-flex align-items-md-center justify-content-md-between">
      <p class="small text-muted" style="margin: 18px 0;">
        Total Records
        <span class="fw-semibold">{{ $paginator->total() }}</span>
      </p>

      <div>
        <ul class="pagination mb-0">
          {{-- First Page Link --}}
           @if ($paginator->onFirstPage())
              <li class="page-item disabled"><span class="page-link">&lsaquo;&lsaquo;</span></li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}" wire:navigate>&lsaquo;&lsaquo;</a>
              </li>
            @endif

          {{-- Previous Page Link --}}
          @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
              <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link" wire:click="gotoPage({{ $paginator->currentPage() - 1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
          @endif

          {{-- Pagination Elements --}}
          @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $start + 5);

            if ($end - $start < 5) {
              $start = max(1, $end - 5);
            }
          @endphp

          @for ($i = $start; $i <= $end; $i++)
              @if ($i == $currentPage)
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
              @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}" wire:navigate>{{ $i }}</a></li>
              @endif
          @endfor

          {{-- Elipsis --}}
          @if ($end < $totalPages)
            <li class="page-item disabled"><span class="page-link">...</span></li>
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($totalPages) }}" wire:navigate>{{ $totalPages }}</a></li>
          @endif

          {{-- Next Page Link --}}
          @if ($paginator->hasMorePages())
            <li class="page-item">
              <a class="page-link" wire:click="gotoPage({{ $paginator->currentPage() + 1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
          @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
              <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
@endif
