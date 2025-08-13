<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Activity;

class LoginController extends Controller
{
    /**
     * Show the customer login form.
     */
    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    /**
     * Handle customer login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active'; // Only active customers can login

        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Get the authenticated customer
            $customer = Auth::guard('customer')->user();
            
            // Update customer login information
            $customer->updateLoginInfo($request);
            
            // Log the login activity
            Activity::log('Customer login', $customer, $customer, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_time' => now()->toDateTimeString(),
                'login_method' => 'web'
            ]);

            return redirect()->intended('/customer/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Handle customer logout request.
     */
    public function logout(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        // Log the logout activity and mark offline before logout
        if ($customer) {
            // Mark customer as offline
            $customer->markOffline();
            
            Activity::log('Customer logout', $customer, $customer, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logout_time' => now()->toDateTimeString()
            ]);
        }

        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/customer/login');
    }
}
