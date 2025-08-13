<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;
use App\Models\Activity;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The guard name for this model.
     */
    protected $guard = 'customer';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
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

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'current_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    /**
     * Check if customer is currently online
     */
    public function isOnline(): bool
    {
        return $this->is_online && $this->last_activity_at && 
               $this->last_activity_at->diffInMinutes(now()) <= 5;
    }
    
    /**
     * Get online customers
     */
    public static function online()
    {
        return static::where('is_online', true)
                    ->where('last_activity_at', '>', now()->subMinutes(5));
    }
    
    /**
     * Update customer's login information
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
     * Update customer's activity timestamp
     */
    public function updateActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }
    
    /**
     * Mark customer as offline
     */
    public function markOffline()
    {
        $this->update(['is_online' => false]);
    }

    /**
     * Get the guard associated with the customer.
     */
    public function getGuardName()
    {
        return $this->guard;
    }
}
