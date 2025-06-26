<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice #{{ $order->id }}</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      margin: 0;
      padding: 0;
      color: #000;
    }

    .container {
      padding: 20px 30px 80px 30px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 20px;
    }

    .header p {
      margin: 2px 0;
      font-size: 11px;
    }

    .info {
      margin-bottom: 15px;
    }

    .info p {
      margin: 3px 0;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .table th,
    .table td {
      border: 1px solid #000;
      padding: 6px;
      font-size: 11px;
    }

    .table th {
      background-color: #f2f2f2;
      text-align: center;
    }

    .table td {
      vertical-align: top;
    }

    .table td:nth-child(2),
    .table td:nth-child(3),
    .table td:nth-child(4) {
      text-align: right;
    }

    .total {
      margin-top: 15px;
      text-align: right;
      font-weight: bold;
      font-size: 12px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 11px;
      padding: 10px;
      border-top: 1px dashed #aaa;
      background-color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    {{-- Header --}}
    <div class="header">
      <h1>{{ config('app.name') }}</h1>
      <p>{{ config('app.address', 'Alamat tidak tersedia') }}</p>
      <p>Telp: {{ config('app.contact', '-') }}</p>
    </div>

    {{-- Informasi --}}
    <div class="info">
      <p><strong>Invoice #: </strong>{{ $order->id }}</p>
      <p><strong>Tanggal: </strong>{{ $order->created_at->format('d M Y') }}</p>
      <p><strong>Customer: </strong>{{ $order->customer->name }}</p>
    </div>

    {{-- Table --}}
    <table class="table">
      <thead>
        <tr>
          <th style="width: 40%;">Produk</th>
          <th style="width: 15%;">Qty</th>
          <th style="width: 20%;">Harga</th>
          <th style="width: 25%;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->orderItems as $item)
          <tr>
            <td>{{ $item->product->name }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td>{{ rupiah($item->price) }}</td>
            <td>{{ rupiah($item->quantity * $item->price) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="total">
      Total: {{ rupiah($order->total_amount) }}
    </div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    Terima kasih sudah belanja di {{ config('app.name') }}!<br>
    Semoga harimu menyenangkan dan kami tunggu order selanjutnya.
  </div>
</body>
</html>
