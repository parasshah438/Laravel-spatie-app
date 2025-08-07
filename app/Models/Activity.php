<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Events\ActivityLogged;

class Activity extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'description',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($activity) {
            // Determine guard based on the causer
            $guard = 'web'; // default
            
            if ($activity->causer) {
                // Use string comparison to avoid circular imports
                $causerClass = get_class($activity->causer);
                
                if ($causerClass === 'App\Models\Customer') {
                    $guard = 'customer';
                } elseif ($causerClass === 'App\Models\User') {
                    // Check if user has admin role
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
        });
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer of the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create a new activity record.
     */
    public static function log(string $description, $subject = null, $causer = null, array $properties = []): self
    {
        return static::create([
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer?->id,
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    /**
     * Get recent activities for a subject.
     */
    public static function forSubject($subject, int $limit = 10)
    {
        return static::where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all recent activities.
     */
    public static function recent(int $limit = 10)
    {
        return static::with(['subject', 'causer'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
