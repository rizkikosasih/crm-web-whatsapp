<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all permissions
        $permissions = [
            'view-dashboard',
            'manage-customers',
            'manage-products',
            'manage-campaigns',
            'manage-orders',
            'view-reports',
            'export-reports',
            'manage-users',
            'manage-roles',
            'manage-menus',
            'manage-templates',
            'manage-whatsapp-api',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Define roles and assign permissions

        // Role: Super Admin (all permissions)
        $superAdminRole = Role::findOrCreate('super-admin', 'web');
        $superAdminRole->syncPermissions($permissions);

        // Role: Admin
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions([
            'view-dashboard',
            'manage-customers',
            'manage-products',
            'manage-campaigns',
            'manage-orders',
        ]);

        // Role: Owner
        $ownerRole = Role::findOrCreate('owner', 'web');
        $ownerRole->syncPermissions([
            'view-dashboard',
            'manage-customers',
            'manage-products',
            'manage-campaigns',
            'manage-orders',
            'view-reports',
            'export-reports',
            'manage-users',
        ]);
    }
}
