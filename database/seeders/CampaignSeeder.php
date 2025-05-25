<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CampaignSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $folder = asset('storage/app/images/campaigns');
    File::cleanDirectory($folder);

    try {
      DB::beginTransaction();

      Campaign::create([
        'title' => 'Diskon Produk',
        'message' => e('👋 Hai {{name}}, kabar baik!

          Kami punya penawaran menarik minggu ini!
          Diskon hingga 50% untuk semua kategori produk.

          Hubungi {{contact_number}} untuk informasi promo lengkap.

          Terima kasih telah menjadi pelanggan setia kami 😊'),
        'created_by' => 1,
      ]);

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      echo $e->getMessage();
    }
  }
}
