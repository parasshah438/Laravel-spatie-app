<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BlogPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all blog permissions
        $permissions = [
            'view blogs',
            'create blogs',
            'edit blogs',
            'delete blogs',
            'publish blogs',
        ];

        // Create permissions for each guard
        foreach ($permissions as $permission) {
            // Admin guard permissions
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
            
            // Web guard permissions (if needed)
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
            
            // Customer guard permissions (if needed)
            Permission::create([
                'name' => $permission,
                'guard_name' => 'customer'
            ]);
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        $editorRole = Role::where('name', 'Editor')->where('guard_name', 'admin')->first();
        if ($editorRole) {
            $editorRole->givePermissionTo(['view blogs', 'create blogs', 'edit blogs']);
        }
    }
}
