<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions grouped by modules.
     */
    public function index()
    {
        $allPermissions = Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
            
        $permissions = $allPermissions->groupBy(function ($permission) {
            // Group by feature name (e.g., "manage blogs" -> "blogs")
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        });
        
        return view('admin.permissions.index', compact('permissions', 'allPermissions'));
    }

    /**
     * Show the form for creating new feature permissions.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created feature with all CRUD permissions.
     */
    public function store(Request $request)
    {
        $request->validate([
            'feature_name' => 'required|string|max:255|regex:/^[a-z\s]+$/',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|string|in:manage,create,edit,view,delete',
        ]);

        $featureName = strtolower(trim($request->feature_name));
        $createdPermissions = [];

        foreach ($request->permissions as $permission) {
            $permissionName = $permission . ' ' . $featureName;
            
            // Check if permission already exists
            if (!Permission::where('name', $permissionName)->where('guard_name', 'admin')->exists()) {
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'admin',
                ]);
                $createdPermissions[] = $permissionName;
            }
        }

        if (empty($createdPermissions)) {
            return redirect()->back()->with('error', 'All permissions already exist for this feature.');
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Feature permissions created successfully: ' . implode(', ', $createdPermissions));
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
    
    /**
     * Seed basic admin permissions for system functionality
     */
    public function seedBasicPermissions()
    {
        try {
            // Define basic permissions needed for admin functionality
            $basicPermissions = [
                // System Management
                'manage admins',
                'manage roles',
                'manage permissions',
                'manage users',
                
                // Content Management
                'manage blogs',
                'manage services',
                'manage sliders',
                
                // Website Management (future features)
                'manage pages',
                'manage categories',
                'manage media',
                
                // Settings & Reports
                'manage settings',
                'view reports',
            ];

            $createdPermissions = [];
            
            // Create permissions if they don't exist
            foreach ($basicPermissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'admin'
                ]);
                
                if ($permission->wasRecentlyCreated) {
                    $createdPermissions[] = $permissionName;
                }
            }

            // Create Super Admin role if it doesn't exist
            $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => 'admin'
            ]);

            // Give all admin permissions to Super Admin role
            $allAdminPermissions = Permission::where('guard_name', 'admin')->get();
            $superAdminRole->syncPermissions($allAdminPermissions);

            // Assign Super Admin role to current admin user if they don't have it
            $currentAdmin = auth('admin')->user();
            if ($currentAdmin && !$currentAdmin->hasRole('Super Admin')) {
                $currentAdmin->assignRole('Super Admin');
            }

            if (count($createdPermissions) > 0) {
                $message = 'Basic permissions seeded successfully! Created: ' . implode(', ', $createdPermissions);
            } else {
                $message = 'All basic permissions already exist. Super Admin role updated with all permissions.';
            }
            
            return redirect()->route('admin.permissions.index')
                ->with('success', $message . ' You now have full admin access.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Error seeding permissions: ' . $e->getMessage());
        }
    }
}
