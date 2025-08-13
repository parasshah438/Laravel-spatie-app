<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Activity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Update user login information
        $user->updateLoginInfo($request);
        
        // Log the login activity
        Activity::log('User login', $user, $user, [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_time' => now()->toDateTimeString(),
            'login_method' => 'web'
        ]);
        
        return redirect()->intended($this->redirectPath());
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            // Mark user as offline
            $user->markOffline();
            
            // Log the logout activity
            Activity::log('User logout', $user, $user, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logout_time' => now()->toDateTimeString()
            ]);
        }
        
        $this->guard()->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
