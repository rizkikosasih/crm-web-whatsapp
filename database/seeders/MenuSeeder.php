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

    // Roles
    $roles = [
      'super-admin' => Role::create(['name' => 'Super Admin']),
      'admin' => Role::create(['name' => 'Admin']),
    ];

    // Header Menus
    $headers = [
      [
        'name' => '-',
        'position' => 1,
        'roles' => ['super-admin', 'admin'],
      ],
      [
        'name' => 'Master',
        'position' => 2,
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Transaksi',
        'position' => 3,
        'roles' => ['super-admin', 'admin'],
      ],
      [
        'name' => 'Laporan',
        'position' => 4,
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Pengaturan',
        'position' => 5,
        'roles' => ['super-admin'],
      ],
    ];

    $headerIds = [];
    $menuRoles = [];

    // Insert headers dan relasi roles-nya
    foreach ($headers as $header) {
      $menu = Menu::create([
        'name' => $header['name'],
        'position' => $header['position'],
      ]);

      $headerIds[$header['name']] = $menu;

      foreach ($header['roles'] as $roleName) {
        $menuRoles[] = [
          'menu_id' => $menu->id,
          'role_id' => $roles[$roleName]->id,
        ];
      }
    }

    // Child Menus
    $menus = [
      [
        'name' => 'Dashboard',
        'icon' => 'fas fa-dashboard',
        'route' => '/dashboard',
        'slug' => 'dashboard',
        'position' => 1,
        'parent' => '-',
        'roles' => ['super-admin', 'admin'],
      ],
      [
        'name' => 'Pelanggan',
        'icon' => 'fas fa-address-book',
        'route' => '/master/customer',
        'slug' => 'master-customer',
        'position' => 1,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Produk',
        'icon' => 'fas fa-box',
        'route' => '/master/product',
        'slug' => 'master-product',
        'position' => 2,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Campaign Broadcast',
        'icon' => 'fas fa-bullhorn',
        'route' => '/master/campaign',
        'slug' => 'master-campaign',
        'position' => 3,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Pesanan',
        'icon' => 'fas fa-shop',
        'route' => '/transaksi/order',
        'slug' => 'transaksi-order',
        'position' => 1,
        'parent' => 'Transaksi',
        'roles' => ['super-admin', 'admin'],
      ],
      [
        'name' => 'Penjualan',
        'icon' => 'fas fa-chart-simple',
        'route' => '/report/order',
        'slug' => 'report-order',
        'position' => 1,
        'parent' => 'Laporan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Penjualan Per Produk',
        'icon' => 'fas fa-table-columns',
        'route' => '/report/product',
        'slug' => 'report-product',
        'position' => 2,
        'parent' => 'Laporan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Template Pesan',
        'icon' => 'fas fa-comment',
        'route' => '/setting/template',
        'slug' => 'setting-template',
        'position' => 1,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'User',
        'icon' => 'fas fa-users',
        'route' => '/setting/user',
        'slug' => 'setting-user',
        'position' => 2,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Role',
        'icon' => 'fas fa-wrench',
        'route' => '/setting/role',
        'slug' => 'setting-role',
        'position' => 3,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Menu',
        'icon' => 'fas fa-bars',
        'route' => '/setting/menu',
        'slug' => 'setting-menu',
        'position' => 4,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Whatsapp Api',
        'icon' => 'fas fa-network-wired',
        'route' => '/setting/whatsapp-api',
        'slug' => 'setting-whatsapp-api',
        'position' => 5,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
    ];

    // Insert child menus and role access
    foreach ($menus as $menuData) {
      $menu = Menu::create([
        'name' => $menuData['name'],
        'icon' => $menuData['icon'],
        'route' => $menuData['route'],
        'slug' => $menuData['slug'],
        'position' => $menuData['position'],
        'parent_id' => $headerIds[$menuData['parent']]->id ?? null,
      ]);

      foreach ($menuData['roles'] as $roleName) {
        $menuRoles[] = [
          'menu_id' => $menu->id,
          'role_id' => $roles[$roleName]->id,
        ];
      }
    }

    MenuRole::insert($menuRoles);
    DB::commit();
  }
}
