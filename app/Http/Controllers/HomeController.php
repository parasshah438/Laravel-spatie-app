<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use App\Services\DashboardStatsService;

class HomeController extends Controller
{
    protected $statsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardStatsService $statsService)
    {
        $this->middleware('auth');
        $this->statsService = $statsService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's recent activities
        $userActivities = $user->recentActivities(5);
        
        // Get user stats
        $stats = [
            'total_activities' => $user->activities()->count(),
            'recent_activities' => $userActivities->count(),
            'member_since' => $user->created_at->format('F Y'),
            'last_activity' => $userActivities->first()?->created_at?->diffForHumans() ?? 'No recent activity'
        ];
        
        // Get system stats
        $systemStats = $this->statsService->getUserCounts('web');
        
        return view('home', compact('user', 'userActivities', 'stats', 'systemStats'));
    }

    /**
     * Get dashboard stats via AJAX for real-time updates.
     */
    public function getStats()
    {
        $user = auth()->user();
        $systemStats = $this->statsService->getUserCounts('web');
        $recentActivities = $this->statsService->getRecentActivities('web', 10);
        
        return response()->json([
            'system_stats' => $systemStats,
            'recent_activities' => $recentActivities,
        ]);
    }
}
