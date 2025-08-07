<?php

use Illuminate\Support\Facades\Route;
use App\Services\DashboardStatsService;
use App\Events\ActivityLogged;
use App\Models\Activity;
use Illuminate\Http\Request;

// Test routes for real-time features
Route::middleware('auth')->group(function () {
    Route::get('/test/activity', function (Request $request) {
        $user = auth()->user();
        
        // Create a test activity
        $activity = Activity::log('Test activity from dashboard', $user, $user, [
            'test' => true,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'message' => 'Test activity created',
            'activity_id' => $activity->id
        ]);
    })->name('test.activity');
    
    Route::get('/test/stats', function () {
        $statsService = new DashboardStatsService();
        $statsService->broadcastStats('web');
        
        return response()->json([
            'message' => 'Stats broadcasted'
        ]);
    })->name('test.stats');
});

// Admin test routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/test/activity', function () {
        $admin = auth('admin')->user();
        
        // Create a test activity
        $activity = Activity::log('Test admin activity', $admin, $admin, [
            'test' => true,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'message' => 'Test admin activity created',
            'activity_id' => $activity->id
        ]);
    })->name('admin.test.activity');
});

// Customer test routes
Route::middleware('customer.auth')->group(function () {
    Route::get('/customer/test/activity', function () {
        $customer = auth('customer')->user();
        
        // Create a test activity
        $activity = Activity::log('Test customer activity', $customer, $customer, [
            'test' => true,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'message' => 'Test customer activity created',
            'activity_id' => $activity->id
        ]);
    })->name('customer.test.activity');
});
