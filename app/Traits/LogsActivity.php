<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('Created account');
        });

        static::updated(function ($model) {
            if ($model->wasChanged('password')) {
                $model->logActivity('Password updated');
            } elseif ($model->wasChanged(['name', 'email'])) {
                $model->logActivity('Profile updated');
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('Account deleted');
        });
    }

    /**
     * Log an activity for this model.
     */
    public function logActivity(string $description, $causer = null, array $properties = []): Activity
    {
        return Activity::log($description, $this, $causer ?: $this, $properties);
    }

    /**
     * Get activities for this model.
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Get recent activities for this model.
     */
    public function recentActivities(int $limit = 10)
    {
        return $this->activities()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
