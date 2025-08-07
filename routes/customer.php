<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\{
    CustomerController
};
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;

// Customer Routes with 'customer' prefix
Route::prefix('customer')->name('customer.')->group(function () {
    // Root customer route - redirect to login if guest, dashboard if authenticated
    Route::get('/', function () {
        if (auth('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return redirect()->route('customer.login');
    })->name('index');
    
    // Guest routes (customer login)
    Route::middleware('customer.guest')->group(function () {
        // Customer Authentication Controller routes
        Route::controller(CustomerLoginController::class)->group(function () {
            Route::get('login', 'showLoginForm')->name('login');
            Route::post('login', 'login');
        });
    });
    
    // Authenticated customer routes
    Route::middleware('customer.auth')->group(function () {
        // Customer Dashboard and Management Controller routes
        Route::controller(CustomerController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('dashboard/stats', 'getStats')->name('dashboard.stats');
            
            // Profile Routes
            Route::get('profile', 'profile')->name('profile');
            Route::put('profile', 'updateProfile')->name('profile.update');
            Route::put('profile/password', 'updatePassword')->name('profile.password.update');
        });
        
        // Customer Authentication Controller authenticated routes
        Route::post('logout', [CustomerLoginController::class, 'logout'])->name('logout');
    });
});
