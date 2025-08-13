<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Activity;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = true; // Only active admins can login

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Get the authenticated admin
            $admin = Auth::guard('admin')->user();
            
            // Update admin login information
            $admin->updateLoginInfo($request);
            
            // Log the login activity
            Activity::log('Admin login', $admin, $admin, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_time' => now()->toDateTimeString(),
                'login_method' => 'web'
            ]);

            return redirect()->intended('/admin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Handle admin logout request.
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Log the logout activity and mark offline before logout
        if ($admin) {
            // Mark admin as offline
            $admin->markOffline();
            
            Activity::log('Admin logout', $admin, $admin, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logout_time' => now()->toDateTimeString()
            ]);
        }

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
