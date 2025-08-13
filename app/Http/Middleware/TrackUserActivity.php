<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Track activity for authenticated users
            if (auth()->check()) {
                $user = auth()->user();
                if (method_exists($user, 'updateActivity')) {
                    $user->updateActivity();
                }
            }
            
            // Track activity for authenticated admins
            if (auth('admin')->check()) {
                $admin = auth('admin')->user();
                if (method_exists($admin, 'updateActivity')) {
                    $admin->updateActivity();
                }
            }
            
            // Track activity for authenticated customers
            if (auth('customer')->check()) {
                $customer = auth('customer')->user();
                if (method_exists($customer, 'updateActivity')) {
                    $customer->updateActivity();
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Log::error('Activity tracking failed: ' . $e->getMessage());
        }
        
        return $next($request);
    }
}
