<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Activity;

class ActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;
    public $guard;

    /**
     * Create a new event instance.
     */
    public function __construct(Activity $activity, string $guard = 'web')
    {
        $this->activity = $activity;
        $this->guard = $guard;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
            new Channel("dashboard.{$this->guard}"),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $causer = $this->activity->causer;
        $causername = 'System';
        
        if ($causer) {
            // Handle different naming patterns safely
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
            'id' => $this->activity->id,
            'description' => $this->activity->description,
            'subject_type' => $this->activity->subject_type,
            'subject_id' => $this->activity->subject_id,
            'causer_name' => $causername,
            'causer_type' => $this->activity->causer_type,
            'properties' => $this->activity->properties ?? [],
            'created_at' => $this->activity->created_at->diffForHumans(),
            'created_at_full' => $this->activity->created_at->format('Y-m-d H:i:s'),
            'guard' => $this->guard,
        ];
    }

    /**
     * Get the event name to broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'activity.logged';
    }
}
