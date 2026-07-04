<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear menus table
        Menu::query()->delete();

        // 2. Insert Header Menus (parent_id = null)
        $headers = [
            'default' => Menu::create([
                'name' => '-',
                'position' => 1,
                'is_sidebar' => true,
                'is_active' => true,
            ]),
            'master' => Menu::create([
                'name' => 'Master',
                'position' => 2,
                'is_sidebar' => true,
                'is_active' => true,
            ]),
            'transaksi' => Menu::create([
                'name' => 'Transaksi',
                'position' => 3,
                'is_sidebar' => true,
                'is_active' => true,
            ]),
            'laporan' => Menu::create([
                'name' => 'Laporan',
                'position' => 4,
                'is_sidebar' => true,
                'is_active' => true,
                'permission' => 'view-reports',
            ]),
            'pengaturan' => Menu::create([
                'name' => 'Pengaturan',
                'position' => 5,
                'is_sidebar' => true,
                'is_active' => true,
            ]),
        ];

        // 3. Insert Child Menus
        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'fas fa-dashboard',
                'route' => '/dashboard',
                'slug' => 'dashboard',
                'position' => 1,
                'parent' => 'default',
                'permission' => 'view-dashboard',
            ],
            [
                'name' => 'Pelanggan',
                'icon' => 'fas fa-address-book',
                'route' => '/master/customer',
                'slug' => 'master-customer',
                'position' => 1,
                'parent' => 'master',
                'permission' => 'manage-customers',
            ],
            [
                'name' => 'Produk',
                'icon' => 'fas fa-box',
                'route' => '/master/product',
                'slug' => 'master-product',
                'position' => 2,
                'parent' => 'master',
                'permission' => 'manage-products',
            ],
            [
                'name' => 'Campaign Broadcast',
                'icon' => 'fas fa-bullhorn',
                'route' => '/master/campaign',
                'slug' => 'master-campaign',
                'position' => 3,
                'parent' => 'master',
                'permission' => 'manage-campaigns',
            ],
            [
                'name' => 'Pesanan',
                'icon' => 'fas fa-shop',
                'route' => '/transaksi/order',
                'slug' => 'transaksi-order',
                'position' => 1,
                'parent' => 'transaksi',
                'permission' => 'manage-orders',
            ],
            [
                'name' => 'Penjualan',
                'icon' => 'fas fa-chart-simple',
                'route' => '/report/order',
                'slug' => 'report-order',
                'position' => 1,
                'parent' => 'laporan',
                'permission' => 'view-reports',
            ],
            [
                'name' => 'Penjualan Per Produk',
                'icon' => 'fas fa-table-columns',
                'route' => '/report/product',
                'slug' => 'report-product',
                'position' => 2,
                'parent' => 'laporan',
                'permission' => 'view-reports',
            ],
            [
                'name' => 'Template Pesan',
                'icon' => 'fas fa-comment',
                'route' => '/setting/template',
                'slug' => 'setting-template',
                'position' => 1,
                'parent' => 'pengaturan',
                'permission' => 'manage-templates',
            ],
            [
                'name' => 'User',
                'icon' => 'fas fa-users',
                'route' => '/setting/user',
                'slug' => 'setting-user',
                'position' => 2,
                'parent' => 'pengaturan',
                'permission' => 'manage-users',
            ],
            [
                'name' => 'Role',
                'icon' => 'fas fa-wrench',
                'route' => '/setting/role',
                'slug' => 'setting-role',
                'position' => 3,
                'parent' => 'pengaturan',
                'permission' => 'manage-roles',
            ],
            [
                'name' => 'Menu',
                'icon' => 'fas fa-bars',
                'route' => '/setting/menu',
                'slug' => 'setting-menu',
                'position' => 4,
                'parent' => 'pengaturan',
                'permission' => 'manage-menus',
            ],
            [
                'name' => 'Whatsapp Api',
                'icon' => 'fas fa-network-wired',
                'route' => '/setting/whatsapp-api',
                'slug' => 'setting-whatsapp-api',
                'position' => 5,
                'parent' => 'pengaturan',
                'permission' => 'manage-whatsapp-api',
            ],
        ];

        foreach ($menus as $m) {
            Menu::create([
                'name' => $m['name'],
                'icon' => $m['icon'],
                'route' => $m['route'],
                'slug' => $m['slug'],
                'position' => $m['position'],
                'parent_id' => $headers[$m['parent']]->id,
                'permission' => $m['permission'],
                'is_sidebar' => true,
                'is_active' => true,
            ]);
        }
    }
}
