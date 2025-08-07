<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Activity;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $statsService;

    public function __construct(DashboardStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Display the customer dashboard.
     */
    public function dashboard()
    {
        $customer = auth('customer')->user();
        
        // Get customer's recent activities
        $customerActivities = $customer->recentActivities(10);
        
        // Customer stats
        $stats = [
            'total_activities' => $customer->activities()->count(),
            'profile_completion' => $this->calculateProfileCompletion($customer),
            'member_since' => $customer->created_at->format('F Y'),
            'last_login' => $customerActivities->where('description', 'Customer login')->first()?->created_at?->diffForHumans() ?? 'Never'
        ];

        // Get system stats for customers
        $systemStats = $this->statsService->getUserCounts('customer');

        return view('customer.dashboard', compact('customer', 'customerActivities', 'stats', 'systemStats'));
    }

    /**
     * Get dashboard stats via AJAX for real-time updates.
     */
    public function getStats()
    {
        $customer = auth('customer')->user();
        $systemStats = $this->statsService->getUserCounts('customer');
        $recentActivities = $this->statsService->getRecentActivities('customer', 10);
        
        return response()->json([
            'system_stats' => $systemStats,
            'recent_activities' => $recentActivities,
        ]);
    }

    /**
     * Display customer profile.
     */
    public function profile()
    {
        $customer = auth('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    /**
     * Update customer profile.
     */
    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $customer->update($request->only([
            'name', 'email', 'company', 'phone', 'address', 
            'city', 'state', 'zip_code', 'country'
        ]));

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update customer password.
     */
    public function updatePassword(Request $request)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->route('customer.profile')
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion($customer)
    {
        $fields = ['name', 'email', 'company', 'phone', 'address', 'city', 'state', 'zip_code', 'country'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($customer->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }
}
