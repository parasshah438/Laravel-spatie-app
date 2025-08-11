<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    /**
     * Display admin activities
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');
            
        // Apply filters
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
        }
        
        $activities = $query->paginate(20);
        
        return view('admin.activities.index', compact('activities', 'filter'));
    }
    
    /**
     * Show activity details
     */
    public function show(Activity $activity)
    {
        // Ensure this activity belongs to an admin
        if ($activity->causer_type !== Admin::class) {
            abort(404);
        }
        
        return view('admin.activities.show', compact('activity'));
    }
    
    /**
     * Clear old activities
     */
    public function clear(Request $request)
    {
        $days = $request->get('days', 30);
        
        $deleted = Activity::where('causer_type', Admin::class)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
            
        // Log this action
        Activity::log(
            "Cleared {$deleted} old admin activities (older than {$days} days)",
            null,
            auth('admin')->user(),
            ['deleted_count' => $deleted, 'days_threshold' => $days]
        );
        
        return redirect()->route('admin.activities.index')
            ->with('success', "Cleared {$deleted} old activities successfully.");
    }
}
