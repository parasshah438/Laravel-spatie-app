<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;
use App\Models\Activity;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $guard = 'admin';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'current_login_at',
        'current_login_ip',
        'is_online',
        'last_activity_at',
        'login_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'last_login_at' => 'datetime',
            'current_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    /**
     * Check if admin is currently online
     */
    public function isOnline(): bool
    {
        return $this->is_online && $this->last_activity_at && 
               $this->last_activity_at->diffInMinutes(now()) <= 5;
    }
    
    /**
     * Get online admins
     */
    public static function online()
    {
        return static::where('is_online', true)
                    ->where('last_activity_at', '>', now()->subMinutes(5));
    }
    
    /**
     * Update admin's login information
     */
    public function updateLoginInfo($request)
    {
        $this->update([
            'last_login_at' => $this->current_login_at,
            'last_login_ip' => $this->current_login_ip,
            'current_login_at' => now(),
            'current_login_ip' => $request->ip(),
            'last_login_user_agent' => $request->userAgent(),
            'is_online' => true,
            'last_activity_at' => now(),
            'login_count' => $this->login_count + 1,
        ]);
    }
    
    /**
     * Update admin's activity timestamp
     */
    public function updateActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }
    
    /**
     * Mark admin as offline
     */
    public function markOffline()
    {
        $this->update(['is_online' => false]);
    }

    /**
     * Get the guard associated with the admin.
     */
    public function getGuardName()
    {
        return $this->guard;
    }
}
