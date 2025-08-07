<?php

namespace App\Services;

use App\Events\StatsUpdated;
use App\Models\User;
use App\Models\Customer;
use App\Models\Activity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    public function getUserCounts(string $guard = 'web'): array
    {
        $cacheKey = "dashboard_stats_{$guard}";
        
        return Cache::remember($cacheKey, 300, function () use ($guard) {
            $stats = [];
            
            if ($guard === 'admin' || $guard === 'web') {
                $stats['total_users'] = User::count();
                $stats['active_users_today'] = Activity::where('causer_type', User::class)
                    ->whereDate('created_at', today())
                    ->distinct('causer_id')
                    ->count();
                $stats['total_customers'] = Customer::count();
                $stats['active_customers_today'] = Activity::where('causer_type', Customer::class)
                    ->whereDate('created_at', today())
                    ->distinct('causer_id')
                    ->count();
            } elseif ($guard === 'customer') {
                $stats['total_customers'] = Customer::count();
                $stats['active_customers_today'] = Activity::where('causer_type', Customer::class)
                    ->whereDate('created_at', today())
                    ->distinct('causer_id')
                    ->count();
                $stats['total_activities'] = Activity::where('causer_type', Customer::class)->count();
                $stats['activities_today'] = Activity::where('causer_type', Customer::class)
                    ->whereDate('created_at', today())
                    ->count();
            }
            
            // Common stats for all guards
            $stats['total_roles'] = Role::count();
            $stats['recent_activities'] = Activity::with(['causer'])
                ->when($guard === 'customer', function ($query) {
                    $query->where('causer_type', Customer::class);
                })
                ->when(in_array($guard, ['admin', 'web']), function ($query) {
                    // Show activities for both users and customers for admin/web
                })
                ->latest()
                ->take(5)
                ->get();
            
            return $stats;
        });
    }

    public function broadcastStats(string $guard = 'web'): void
    {
        $stats = $this->getUserCounts($guard);
        
        // Remove recent_activities from broadcast (too much data)
        $broadcastStats = collect($stats)->except('recent_activities')->toArray();
        
        broadcast(new StatsUpdated($broadcastStats, $guard));
    }

    public function clearStatsCache(string $guard = null): void
    {
        if ($guard) {
            Cache::forget("dashboard_stats_{$guard}");
        } else {
            // Clear all guards
            Cache::forget('dashboard_stats_web');
            Cache::forget('dashboard_stats_admin'); 
            Cache::forget('dashboard_stats_customer');
        }
    }

    public function getRecentActivities(string $guard = 'web', int $limit = 10): array
    {
        $query = Activity::with(['causer']);
        
        if ($guard === 'customer') {
            $query->where('causer_type', Customer::class);
        } elseif (in_array($guard, ['admin', 'web'])) {
            // Show all activities for admin/web guards
        }
        
        $activities = $query->latest()->take($limit)->get();
        
        return $activities->map(function ($activity) {
            $causer = $activity->causer;
            $causername = 'System';
            
            if ($causer) {
                try {
                    if (isset($causer->name)) {
                        $causername = $causer->name;
                    } elseif (isset($causer->first_name)) {
                        $causername = $causer->first_name . ' ' . ($causer->last_name ?? '');
                    } elseif (method_exists($causer, 'getFullNameAttribute')) {
                        $causername = $causer->getFullNameAttribute();
                    } else {
                        $causername = class_basename(get_class($causer)) . ' #' . $causer->id;
                    }
                } catch (\Exception $e) {
                    $causername = 'Unknown User';
                }
            }
            
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'causer_name' => $causername,
                'causer_type' => $activity->causer_type,
                'subject_type' => $activity->subject_type,
                'created_at' => $activity->created_at->diffForHumans(),
                'created_at_full' => $activity->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}
