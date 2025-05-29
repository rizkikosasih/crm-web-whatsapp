<html>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <table border="1">
      <thead>
        <tr>
          <th style="width: 50px;">No</th>
          <th>Order ID</th>
          <th style="width: 100px;">Tanggal</th>
          <th style="width: 250px;">Customer</th>
          <th style="width: 150px;">Status</th>
          <th style="width: 250px;">Produk</th>
          <th>Qty</th>
          <th style="width: 100px;">Harga</th>
          <th style="width: 120px;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @foreach($orders as $order)
          @php $itemCount = $order->orderItems->count(); @endphp
          @foreach($order->orderItems as $itemIndex => $item)
            <tr>
              @if ($itemIndex === 0)
                <td valign="top" style="text-align: center;" rowspan="{{ $itemCount }}">{{ $no++ }}</td>
                <td valign="top" style="text-align: center;" rowspan="{{ $itemCount }}">{{ $order->id }}</td>
                <td valign="top" style="text-align: right;" rowspan="{{ $itemCount }}">{{ $order->order_date }}</td>
                <td valign="top" rowspan="{{ $itemCount }}">{{ $order->customer->name }}</td>
                <td valign="top" style="text-align: center;" rowspan="{{ $itemCount }}">{{ $statusList[$order->status] }}</td>
              @endif
              <td valign="top">{{ $item->product->name }}</td>
              <td valign="top" style="text-align: center;">{{ $item->quantity }}</td>
              <td valign="top" style="text-align: right;">{{ rp($item->price) }}</td>
              <td valign="top" style="text-align: right;">{{ rp($item->price * $item->quantity) }}</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  </body>
</html>
