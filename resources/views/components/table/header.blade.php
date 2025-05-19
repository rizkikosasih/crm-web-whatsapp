@props(['columns' => []])

@if ($columns)
  <thead class="text-center">
    <tr>
      @foreach ($columns as $column)
        <th class="{{ arrayKey($column, 'class') }}" style="{{ arrayKey($column, 'style') }}">{!! $column['name'] !!}</th>
      @endforeach
    </tr>
  </thead>
@endif
