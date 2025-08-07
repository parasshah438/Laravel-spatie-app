<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for admin guard
        $adminPermissions = [
            'admin-list',
            'admin-create',
            'admin-edit',
            'admin-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Create permissions for web guard (users)
        $userPermissions = [
            'profile-edit',
            'profile-view',
        ];

        foreach ($userPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles for admin guard
        $superAdminRole = Role::create([
            'name' => 'superadmin',
            'guard_name' => 'admin'
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);

        // Create user role for web guard
        $userRole = Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        // Assign all admin permissions to superadmin
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        // Assign limited permissions to admin role
        $adminRole->givePermissionTo([
            'user-list',
            'user-create',
            'user-edit',
        ]);

        // Assign user permissions to user role
        $userRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

        // Create default super admin
        $superAdmin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('password'),
            'status' => true,
        ]);

        $superAdmin->assignRole('superadmin');

        // Create default admin
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'status' => true,
        ]);

        $admin->assignRole('admin');
    }
}
