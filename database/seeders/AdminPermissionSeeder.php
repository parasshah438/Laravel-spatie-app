<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AdminPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create basic permissions for admin guard
        $permissions = [
            // System Management
            'manage users',
            'manage roles', 
            'manage permissions',
            
            // Content Management
            'manage blogs',
            'manage services',
            'manage sliders',
            
            // Website Management
            'manage pages',
            'manage categories',
            'manage media',
            
            // Settings & Reports
            'manage settings',
            'view reports',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        // Give all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // Assign Super Admin role to the first admin user
        $admin = Admin::first();
        if ($admin && !$admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }

        echo "Permissions and roles seeded successfully!\n";
        echo "Total permissions created: " . Permission::where('guard_name', 'admin')->count() . "\n";
        echo "Super Admin role assigned to: " . ($admin ? $admin->email : 'No admin user found') . "\n";
    }
}
