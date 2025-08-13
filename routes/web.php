<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController,
    ResetPasswordController
};

Route::get('/', function () {
    return view('welcome');
});

// User Routes with 'user' prefix
Route::prefix('user')->name('user.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        // Authentication Controller routes
        Route::controller(LoginController::class)->group(function () {
            Route::get('login', 'showLoginForm')->name('login');
            Route::post('login', 'login');
        });
        
        Route::controller(RegisterController::class)->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
        });
        
        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get('forgot-password', 'showLinkRequestForm')->name('password.request');
            Route::post('forgot-password', 'sendResetLinkEmail')->name('password.email');
        });
        
        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('reset-password/{token}', 'showResetForm')->name('password.reset');
            Route::post('reset-password', 'reset')->name('password.update');
        });
    });
    
    // Authenticated routes
    Route::middleware('auth')->group(function () {
        // User Controller routes
        Route::controller(UserController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('profile', 'profile')->name('profile');
            Route::put('profile', 'updateProfile')->name('profile.update');
        });
        
        // Authentication logout route
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// Legacy route compatibility (redirect to new prefixed routes)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/stats', [App\Http\Controllers\HomeController::class, 'getStats'])->name('home.stats')->middleware('auth');

// Backward compatibility routes
Auth::routes(['register' => true]); // Disable default registration for compatibility

// Admin Routes (Separate Guard)
require __DIR__.'/admin.php';