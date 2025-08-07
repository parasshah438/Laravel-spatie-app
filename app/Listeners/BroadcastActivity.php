<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Activity;

class BroadcastActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (property_exists($event, 'activity') && $event->activity instanceof Activity) {
            $activity = $event->activity;
            
            // Determine the guard from the causer
            $guard = 'web'; // default
            
            if ($activity->causer) {
                $causerClass = get_class($activity->causer);
                
                // Check if it's a customer
                if ($causerClass === 'App\Models\Customer') {
                    $guard = 'customer';
                } elseif ($causerClass === 'App\Models\User') {
                    // You can add logic here to determine if it's admin or regular user
                    // For now, we'll check if they have admin role
                    try {
                        if (method_exists($activity->causer, 'hasRole') && $activity->causer->hasRole('Admin')) {
                            $guard = 'admin';
                        } else {
                            $guard = 'web';
                        }
                    } catch (\Exception $e) {
                        $guard = 'web';
                    }
                }
            }
            
            // Broadcast the activity
            broadcast(new ActivityLogged($activity, $guard));
        }
    }
}
