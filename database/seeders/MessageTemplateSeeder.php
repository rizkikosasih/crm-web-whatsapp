<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    MessageTemplate::create([
      'body' => e('Info Produk
        Nama: {{name}}
        SKU: {{sku}}
        Harga: {{price}}
        Stok: {{stock}}
        Deskripsi: {{description}}'),
      'title' => 'Informasi Produk',
      'type' => 'product',
    ]);

    MessageTemplate::create([
      'body' => e('Halo {{customer_name}}, Terima kasih telah melakukan pemesanan di {{store_name}}.

        🧾 Rincian Pesanan:
        Nomor Pesanan: {{order_number}}
        Tanggal Pesanan: {{order_date}}

        📦 Produk:
        {{product_list}}

        💵 Total Pembayaran: {{order_total}}

        Silakan lakukan pembayaran ke rekening yang telah kami sediakan dan **upload bukti pembayaran** ke nomor berikut: {{contact_number}}.
        Pesanan Anda akan kami proses setelah pembayaran dikonfirmasi.

        Jika ada pertanyaan, silakan hubungi kami di {{contact_number}}.

        Terima kasih 🙏
        {{store_name}}'),
      'title' => 'Pesanan Dibuat',
      'type' => 'order',
    ]);

    MessageTemplate::create([
      'body' => e('Halo {{customer_name}},

        Terima kasih, kami telah menerima pembayaran Anda untuk pesanan berikut:
        🧾 Nomor Pesanan: {{order_number}}
        Total Pembayaran: {{order_total}}

        ✅ Pesanan Anda sedang kami proses dan akan segera dikirim.

        Kami akan menginformasikan apabila pesanan sudah dikirim.

        Terima kasih telah berbelanja di {{store_name}} 🙏
        Jika ada pertanyaan, silakan hubungi kami di {{contact_number}}.'),
      'title' => 'Pesanan Dibayar',
      'type' => 'order',
    ]);

    MessageTemplate::create([
      'body' => e('Halo {{customer_name}},

        Pesanan Anda dengan nomor pesanan {{order_number}} saat ini sedang dalam proses pengiriman.

        Harap ditunggu, kurir akan segera mengantarkan pesanan ke alamat tujuan.

        Terima kasih telah berbelanja di {{store_name}} 🙏
        Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami di {{contact_number}}.'),
      'title' => 'Pesanan Dalam Pengiriman',
      'type' => 'order',
    ]);

    MessageTemplate::create([
      'body' => e('Halo {{customer_name}},

        Pesanan Anda dengan nomor pesanan {{order_number}} telah berhasil diselesaikan.
        Kami harap Anda puas dengan produk dan layanan kami 😊

        Terima kasih telah berbelanja di {{store_name}}.
        Jika ada pertanyaan atau ulasan, silakan hubungi kami di {{contact_number}}.

        Sampai jumpa di pesanan berikutnya!'),
      'title' => 'Pesanan Selesai',
      'type' => 'order',
    ]);

    MessageTemplate::create([
      'body' => e('Halo {{customer_name}},

        Kami ingin memberitahukan bahwa pesanan Anda dengan nomor pesanan {{order_number}} telah dibatalkan.
        Kami mohon maaf atas ketidaknyamanan yang terjadi.

        Jika Anda memiliki pertanyaan lebih lanjut atau ingin melakukan pemesanan ulang, silakan hubungi kami di {{contact_number}}.

        Terima kasih atas perhatian Anda, dan semoga bisa melayani Anda lagi di lain kesempatan.'),
      'title' => 'Pesanan Dibatalkan',
      'type' => 'order',
    ]);
  }
}
