<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserAnalyticsController extends Controller
{
    /**
     * Display user registration analytics
     */
    public function index(Request $request)
    {
        // Get date filters
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $dateRange = $request->get('date_range', 'last_30_days');
        
        // Apply predefined date ranges
        [$startDate, $endDate] = $this->applyDateRange($dateRange, $startDate, $endDate);
        
        // Get analytics data
        $analytics = $this->getUserAnalytics($startDate, $endDate);
        $chartData = $this->getChartData($startDate, $endDate);
        $recentUsers = $this->getRecentUsers(10);
        
        return view('admin.analytics.users', compact(
            'analytics', 
            'chartData', 
            'recentUsers', 
            'startDate', 
            'endDate', 
            'dateRange'
        ));
    }
    
    /**
     * Apply predefined date ranges
     */
    private function applyDateRange($range, $startDate, $endDate)
    {
        switch ($range) {
            case 'today':
                return [now()->format('Y-m-d'), now()->format('Y-m-d')];
            case 'yesterday':
                return [now()->subDay()->format('Y-m-d'), now()->subDay()->format('Y-m-d')];
            case 'last_7_days':
                return [now()->subDays(6)->format('Y-m-d'), now()->format('Y-m-d')];
            case 'last_30_days':
                return [now()->subDays(29)->format('Y-m-d'), now()->format('Y-m-d')];
            case 'this_month':
                return [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')];
            case 'last_month':
                $lastMonth = now()->subMonth();
                return [$lastMonth->startOfMonth()->format('Y-m-d'), $lastMonth->endOfMonth()->format('Y-m-d')];
            case 'this_year':
                return [now()->startOfYear()->format('Y-m-d'), now()->format('Y-m-d')];
            case 'custom':
            default:
                return [$startDate, $endDate];
        }
    }
    
    /**
     * Get user analytics data
     */
    private function getUserAnalytics($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        return [
            // Users
            'total_users' => User::count(),
            'users_in_period' => User::whereBetween('created_at', [$start, $end])->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'users_this_month' => User::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)->count(),
            
            // Customers
            'total_customers' => Customer::count(),
            'customers_in_period' => Customer::whereBetween('created_at', [$start, $end])->count(),
            'customers_today' => Customer::whereDate('created_at', today())->count(),
            'customers_this_month' => Customer::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)->count(),
            
            // Growth calculations
            'user_growth_rate' => $this->calculateGrowthRate('User', $start, $end),
            'customer_growth_rate' => $this->calculateGrowthRate('Customer', $start, $end),
            
            // Activity stats
            'total_activities_in_period' => Activity::whereBetween('created_at', [$start, $end])->count(),
            'most_active_day' => $this->getMostActiveDay($start, $end),
        ];
    }
    
    /**
     * Calculate growth rate
     */
    private function calculateGrowthRate($model, $start, $end)
    {
        $modelClass = "App\\Models\\{$model}";
        $days = $start->diffInDays($end) ?: 1;
        
        // Current period count
        $currentPeriod = $modelClass::whereBetween('created_at', [$start, $end])->count();
        
        // Previous period count
        $prevStart = $start->copy()->subDays($days);
        $prevEnd = $start->copy()->subDay();
        $previousPeriod = $modelClass::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        
        if ($previousPeriod === 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }
        
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }
    
    /**
     * Get chart data for the period
     */
    private function getChartData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end);
        
        // If period is too long, group by weeks or months
        if ($days > 60) {
            return $this->getMonthlyChartData($start, $end);
        } elseif ($days > 14) {
            return $this->getWeeklyChartData($start, $end);
        } else {
            return $this->getDailyChartData($start, $end);
        }
    }
    
    /**
     * Get daily chart data
     */
    private function getDailyChartData($start, $end)
    {
        $labels = [];
        $userData = [];
        $customerData = [];
        
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $labels[] = $date->format('M j');
            
            $userData[] = User::whereDate('created_at', $date)->count();
            $customerData[] = Customer::whereDate('created_at', $date)->count();
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $userData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Customers',
                    'data' => $customerData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ]
            ]
        ];
    }
    
    /**
     * Get weekly chart data
     */
    private function getWeeklyChartData($start, $end)
    {
        $labels = [];
        $userData = [];
        $customerData = [];
        
        $current = $start->copy()->startOfWeek();
        while ($current->lte($end)) {
            $weekEnd = $current->copy()->endOfWeek();
            if ($weekEnd->gt($end)) $weekEnd = $end;
            
            $labels[] = $current->format('M j') . ' - ' . $weekEnd->format('M j');
            
            $userData[] = User::whereBetween('created_at', [$current, $weekEnd])->count();
            $customerData[] = Customer::whereBetween('created_at', [$current, $weekEnd])->count();
            
            $current->addWeek();
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $userData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Customers',
                    'data' => $customerData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ]
            ]
        ];
    }
    
    /**
     * Get monthly chart data
     */
    private function getMonthlyChartData($start, $end)
    {
        $labels = [];
        $userData = [];
        $customerData = [];
        
        $current = $start->copy()->startOfMonth();
        while ($current->lte($end)) {
            $monthEnd = $current->copy()->endOfMonth();
            if ($monthEnd->gt($end)) $monthEnd = $end;
            
            $labels[] = $current->format('M Y');
            
            $userData[] = User::whereBetween('created_at', [$current, $monthEnd])->count();
            $customerData[] = Customer::whereBetween('created_at', [$current, $monthEnd])->count();
            
            $current->addMonth();
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $userData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Customers',
                    'data' => $customerData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ]
            ]
        ];
    }
    
    /**
     * Get most active registration day
     */
    private function getMostActiveDay($start, $end)
    {
        $userCounts = User::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('count', 'desc')
            ->first();
            
        $customerCounts = Customer::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('count', 'desc')
            ->first();
        
        $totalUsers = $userCounts ? $userCounts->count : 0;
        $totalCustomers = $customerCounts ? $customerCounts->count : 0;
        
        if ($totalUsers >= $totalCustomers && $userCounts) {
            return [
                'date' => Carbon::parse($userCounts->date)->format('M j, Y'),
                'count' => $totalUsers,
                'type' => 'Users'
            ];
        } elseif ($customerCounts) {
            return [
                'date' => Carbon::parse($customerCounts->date)->format('M j, Y'),
                'count' => $totalCustomers,
                'type' => 'Customers'
            ];
        }
        
        return null;
    }
    
    /**
     * Get recent users
     */
    private function getRecentUsers($limit = 10)
    {
        return [
            'users' => User::latest()->limit($limit)->get(),
            'customers' => Customer::latest()->limit($limit)->get(),
        ];
    }
    
    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $analytics = $this->getUserAnalytics($startDate, $endDate);
        
        // Log the export activity
        Activity::log(
            'Exported user analytics report',
            null, // System-wide activity, no specific subject
            auth('admin')->user(),
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_users_in_period' => $analytics['users_in_period'],
                'total_customers_in_period' => $analytics['customers_in_period']
            ]
        );
        
        return response()->json([
            'message' => 'Analytics data exported successfully',
            'data' => $analytics,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }
}
