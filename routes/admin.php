<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminController,
    RoleController,
    PermissionController,
    BlogController,
    ServiceController,
    SliderController,
    AdminActivityController,
    UserAnalyticsController
};

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

// Admin Routes with 'admin' prefix
Route::prefix('admin')->name('admin.')->group(function () {
    // Root admin route - redirect to login if guest, dashboard if authenticated
    Route::get('/', function () {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('index');
    
    // Guest routes (admin login)
    Route::middleware('admin.guest')->group(function () {
        // Admin Authentication Controller routes
        Route::controller(AdminLoginController::class)->group(function () {
            Route::get('login', 'showLoginForm')->name('login');
            Route::post('login', 'login');
        });
    });
    
    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        // Admin Dashboard and Management Controller routes
        Route::controller(AdminController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('dashboard/stats', 'getStats')->name('dashboard.stats');
            
            // Profile Routes
            Route::get('profile', 'profile')->name('profile');
            Route::put('profile', 'updateProfile')->name('profile.update');
            Route::put('profile/password', 'updatePassword')->name('profile.password.update');
            
            // Admin Management Routes
            Route::get('admins', 'index')->name('admins.index');
            Route::get('admins/create', 'create')->name('admins.create');
            Route::post('admins', 'store')->name('admins.store');
            Route::get('admins/{admin}', 'show')->name('admins.show');
            Route::get('admins/{admin}/edit', 'edit')->name('admins.edit');
            Route::put('admins/{admin}', 'update')->name('admins.update');
            Route::delete('admins/{admin}', 'destroy')->name('admins.destroy');
            
            // User Management Routes (Admin can manage users)
            Route::get('users', 'users')->name('users.index');
        });
        
        // Role Management Controller routes
        Route::controller(RoleController::class)->group(function () {
            Route::get('roles', 'index')->name('roles.index');
            Route::get('roles/create', 'create')->name('roles.create');
            Route::post('roles', 'store')->name('roles.store');
            Route::get('roles/{role}', 'show')->name('roles.show');
            Route::get('roles/{role}/edit', 'edit')->name('roles.edit');
            Route::put('roles/{role}', 'update')->name('roles.update');
            Route::delete('roles/{role}', 'destroy')->name('roles.destroy');
        });
        
        // Permission Management Controller routes
        Route::controller(PermissionController::class)->group(function () {
            Route::get('permissions', 'index')->name('permissions.index');
            Route::get('permissions/create', 'create')->name('permissions.create');
            Route::post('permissions', 'store')->name('permissions.store');
            Route::delete('permissions/{permission}', 'destroy')->name('permissions.destroy');
            
            // Seed basic permissions route
            Route::get('permissions/seed-basic', 'seedBasicPermissions')->name('permissions.seed-basic');
        });
        
        // Admin Activity Log routes
        Route::controller(AdminActivityController::class)->group(function () {
            Route::get('activities', 'index')->name('activities.index');
            Route::get('activities/{activity}', 'show')->name('activities.show');
            Route::delete('activities/clear', 'clear')->name('activities.clear');
        });
        
        // User Analytics routes
        Route::controller(UserAnalyticsController::class)->group(function () {
            Route::get('analytics/users', 'index')->name('analytics.users');
            Route::get('analytics/users/export', 'export')->name('analytics.users.export');
        });
        
        // Debug route to check admin permissions
        Route::get('debug-permissions', function () {
            $admin = auth('admin')->user();
            
            return response()->json([
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'admin_roles' => $admin->roles->pluck('name')->toArray(),
                'admin_permissions' => $admin->getAllPermissions()->pluck('name')->toArray(),
                'all_admin_permissions' => \Spatie\Permission\Models\Permission::where('guard_name', 'admin')->pluck('name')->toArray(),
                'all_admin_roles' => \Spatie\Permission\Models\Role::where('guard_name', 'admin')->pluck('name')->toArray(),
            ]);
        })->name('debug-permissions');
        
        // Content Management Routes
        Route::resource('blogs', BlogController::class);
        Route::patch('blogs/{blog}/publish', [BlogController::class, 'publish'])->name('blogs.publish');
        Route::patch('blogs/{blog}/unpublish', [BlogController::class, 'unpublish'])->name('blogs.unpublish');
        
        Route::resource('services', ServiceController::class);
        Route::resource('sliders', SliderController::class);
        
        // Additional Content Management Routes (create controllers as needed)
        // Route::resource('pages', PageController::class);
        // Route::resource('categories', CategoryController::class);
        // Route::resource('media', MediaController::class);
        
        // Settings Routes (create controller as needed)
        // Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        // Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Reports Routes (create controller as needed)  
        // Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        // Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
        
        // Admin Authentication Controller authenticated routes
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});
