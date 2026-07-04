@props (['columns' => []])

@if ($columns)
  <thead class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700/50">
    <tr>
      @foreach ($columns as $column)
        <th
          class="px-6 py-3 text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider {{ arrayKey($column, 'class') ?? 'text-left' }}"
          style="{{ arrayKey($column, 'style') }}">
          {!! $column['name'] !!}
        </th>
      @endforeach
    </tr>
  </thead>
@endif
