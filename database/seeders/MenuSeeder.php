<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::beginTransaction();

    //Head Menu
    $menu = Menu::create([
      'name' => '',
      'position' => 1,
    ]);

    $master = Menu::create([
      'name' => 'Master',
      'position' => 2,
    ]);

    $transaksi = Menu::create([
      'name' => 'Transaksi',
      'position' => 3,
    ]);

    $laporan = Menu::create([
      'name' => 'Laporan',
      'position' => 4,
    ]);

    $setting = Menu::create([
      'name' => 'Pengaturan',
      'position' => 5,
    ]);

    //Menu
    $dashboard = Menu::create([
      'name' => 'Dashboard',
      'icon' => 'fas fa-dashboard',
      'route' => '/dashboard',
      'slug' => 'dashboard',
      'position' => 1,
      'parent_id' => $menu->id,
    ]);

    $customer = Menu::create([
      'name' => 'Pelanggan',
      'icon' => 'fas fa-address-book',
      'route' => '/customer',
      'slug' => 'customer',
      'position' => 1,
      'parent_id' => $master->id,
    ]);

    $product = Menu::create([
      'name' => 'Produk',
      'icon' => 'fas fa-box',
      'route' => '/product',
      'slug' => 'product',
      'position' => 2,
      'parent_id' => $master->id,
    ]);

    $campaign = Menu::create([
      'name' => 'Campaign Broadcast',
      'icon' => 'fas fa-bullhorn',
      'route' => '/campaign',
      'slug' => 'campaign',
      'position' => 3,
      'parent_id' => $master->id,
    ]);

    $order = Menu::create([
      'name' => 'Pesanan',
      'icon' => 'fas fa-shop',
      'route' => '/order',
      'slug' => 'order',
      'position' => 1,
      'parent_id' => $transaksi->id,
    ]);

    $messageOut = Menu::create([
      'name' => 'Pesan Keluar',
      'icon' => 'fas fa-envelope-open',
      'route' => '/message/out',
      'slug' => 'message-out',
      'position' => 2,
      'parent_id' => $transaksi->id,
    ]);

    $penjualan = Menu::create([
      'name' => 'Penjualan',
      'icon' => 'fas fa-chart-simple',
      'route' => '/report/order',
      'slug' => 'report-order',
      'position' => 1,
      'parent_id' => $laporan->id,
    ]);

    $penjualanPerProduk = Menu::create([
      'name' => 'Penjualan Per Produk',
      'icon' => 'fas fa-table-columns',
      'route' => '/report/product',
      'slug' => 'report-product',
      'position' => 2,
      'parent_id' => $laporan->id,
    ]);

    $messageTemplate = Menu::create([
      'name' => 'Template Pesan',
      'icon' => 'fas fa-comment',
      'route' => '/message/template',
      'slug' => 'message-template',
      'position' => 1,
      'parent_id' => $setting->id,
    ]);

    $user = Menu::create([
      'name' => 'User',
      'icon' => 'fas fa-users',
      'route' => '/setting/user',
      'slug' => 'setting-user',
      'position' => 2,
      'parent_id' => $setting->id,
    ]);

    $role = Menu::create([
      'name' => 'Role',
      'icon' => 'fas fa-wrench',
      'route' => '/setting/role',
      'slug' => 'setting-role',
      'position' => 3,
      'parent_id' => $setting->id,
    ]);

    $submenu = Menu::create([
      'name' => 'Menu',
      'icon' => 'fas fa-bars',
      'route' => '/setting/menu',
      'slug' => 'setting-menu',
      'position' => 4,
      'parent_id' => $setting->id,
    ]);

    $superAdmin = Role::create(['name' => 'Super Admin']);
    $admin = Role::create(['name' => 'Admin']);

    MenuRole::insert([
      /**
       * Super Admin
       * Header
       */
      ['menu_id' => $menu->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $master->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $transaksi->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $laporan->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $setting->id, 'role_id' => $superAdmin->id],

      /** Menu */
      ['menu_id' => $dashboard->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $customer->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $product->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $order->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $campaign->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $messageOut->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $penjualan->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $penjualanPerProduk->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $messageTemplate->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $user->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $role->id, 'role_id' => $superAdmin->id],
      ['menu_id' => $submenu->id, 'role_id' => $superAdmin->id],

      /**
       * Admin
       * Header
       */
      ['menu_id' => $menu->id, 'role_id' => $admin->id],

      /** Menu */
      ['menu_id' => $dashboard->id, 'role_id' => $admin->id],
    ]);

    DB::commit();
  }
}
