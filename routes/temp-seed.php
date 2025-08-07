<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

// Temporary route to seed admin permissions
Route::get('/admin/seed-permissions', function () {
    try {
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
        $createdPermissions = [];
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
            $createdPermissions[] = $perm->name;
        }

        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        // Give all permissions to Super Admin
        $allPermissions = Permission::where('guard_name', 'admin')->get();
        $superAdminRole->syncPermissions($allPermissions);

        // Assign Super Admin role to the first admin user
        $admin = Admin::first();
        if ($admin) {
            $admin->assignRole('Super Admin');
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions and roles seeded successfully!',
            'permissions_created' => $createdPermissions,
            'total_permissions' => $allPermissions->count(),
            'admin_email' => $admin ? $admin->email : 'No admin user found',
            'admin_roles' => $admin ? $admin->roles->pluck('name') : []
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error seeding permissions: ' . $e->getMessage()
        ]);
    }
});
