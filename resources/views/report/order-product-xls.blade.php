<html>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <table border="1">
      <thead>
        <tr>
          <th style="width: 50px;">No</th>
          <th>Produk ID</th>
          <th style="width: 250px;">Nama Produk</th>
          <th style="width: 100px;">Total Quantity</th>
          <th style="width: 250px;">Total Income</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @foreach($items as $item)
          <tr>
            <td valign="top" style="text-align: center;">{{ $no++ }}</td>
            <td valign="top" style="text-align: center;">{{ $item->product_id }}</td>
            <td valign="top">{{ $item->product->name }}</td>
            <td valign="top" style="text-align: center;">{{ $item->total_quantity }}</td>
            <td valign="top" style="text-align: right;">{{ rp($item->total_income) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
