<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;
use App\Models\Activity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
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
     * Check if user is currently online
     */
    public function isOnline(): bool
    {
        return $this->is_online && $this->last_activity_at && 
               $this->last_activity_at->diffInMinutes(now()) <= 5;
    }
    
    /**
     * Get online users
     */
    public static function online()
    {
        return static::where('is_online', true)
                    ->where('last_activity_at', '>', now()->subMinutes(5));
    }
    
    /**
     * Update user's login information
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
     * Update user's activity timestamp
     */
    public function updateActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }
    
    /**
     * Mark user as offline
     */
    public function markOffline()
    {
        $this->update(['is_online' => false]);
    }
}
