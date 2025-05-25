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
      '' => 1,
      'Master' => 2,
      'Transaksi' => 3,
      'Laporan' => 4,
      'Pengaturan' => 5,
    ];

    $headerIds = [];
    foreach ($headers as $name => $position) {
      $headerIds[$name] = Menu::create([
        'name' => $name,
        'position' => $position,
      ]);
    }

    // Child Menus
    $menus = [
      [
        'name' => 'Dashboard',
        'icon' => 'fas fa-dashboard',
        'route' => '/dashboard',
        'slug' => 'dashboard',
        'position' => 1,
        'parent' => '',
        'roles' => ['super-admin', 'admin'],
      ],
      [
        'name' => 'Pelanggan',
        'icon' => 'fas fa-address-book',
        'route' => '/customer',
        'slug' => 'customer',
        'position' => 1,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Produk',
        'icon' => 'fas fa-box',
        'route' => '/product',
        'slug' => 'product',
        'position' => 2,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Campaign Broadcast',
        'icon' => 'fas fa-bullhorn',
        'route' => '/campaign',
        'slug' => 'campaign',
        'position' => 3,
        'parent' => 'Master',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Pesanan',
        'icon' => 'fas fa-shop',
        'route' => '/order',
        'slug' => 'order',
        'position' => 1,
        'parent' => 'Transaksi',
        'roles' => ['super-admin'],
      ],
      [
        'name' => 'Pesan Keluar',
        'icon' => 'fas fa-envelope-open',
        'route' => '/message/out',
        'slug' => 'message-out',
        'position' => 2,
        'parent' => 'Transaksi',
        'roles' => ['super-admin'],
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
        'icon' => 'fab fa-whatsapp',
        'route' => '/setting/whatsapp-api',
        'slug' => 'setting-whatsapp-api',
        'position' => 5,
        'parent' => 'Pengaturan',
        'roles' => ['super-admin'],
      ],
    ];

    // Insert child menus and role access
    $menuRoles = [];
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

    // Header Role Access
    foreach ($headers as $name => $pos) {
      $menuRoles[] = [
        'menu_id' => $headerIds[$name]->id,
        'role_id' => $roles['super-admin']->id,
      ];

      // Only '' (Dashboard group) is for admin
      if ($name === '') {
        $menuRoles[] = [
          'menu_id' => $headerIds[$name]->id,
          'role_id' => $roles['admin']->id,
        ];
      }
    }

    MenuRole::insert($menuRoles);
    DB::commit();
  }
}
