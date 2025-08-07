<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and admins
        $users = User::take(5)->get();
        $admins = Admin::take(3)->get();

        $activities = [
            'Login to account',
            'Profile updated',
            'Password changed',
            'Logout from account',
            'Account settings modified',
            'Email verified',
            'Viewed dashboard',
            'Updated preferences'
        ];

        // Create activities for users
        foreach ($users as $user) {
            for ($i = 0; $i < rand(3, 8); $i++) {
                Activity::create([
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'causer_type' => User::class,
                    'causer_id' => $user->id,
                    'description' => $activities[array_rand($activities)],
                    'properties' => [
                        'ip_address' => '127.0.0.1',
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ],
                    'created_at' => Carbon::now()->subHours(rand(1, 72)),
                    'updated_at' => Carbon::now()->subHours(rand(1, 72)),
                ]);
            }
        }

        // Create activities for admins
        $adminActivities = [
            'Admin login',
            'Viewed admin dashboard',
            'Managed user accounts',
            'Updated role permissions',
            'Created new admin',
            'Modified system settings',
            'Exported user data',
            'Admin logout'
        ];

        foreach ($admins as $admin) {
            for ($i = 0; $i < rand(5, 12); $i++) {
                Activity::create([
                    'subject_type' => Admin::class,
                    'subject_id' => $admin->id,
                    'causer_type' => Admin::class,
                    'causer_id' => $admin->id,
                    'description' => $adminActivities[array_rand($adminActivities)],
                    'properties' => [
                        'ip_address' => '192.168.1.' . rand(1, 255),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ],
                    'created_at' => Carbon::now()->subHours(rand(1, 48)),
                    'updated_at' => Carbon::now()->subHours(rand(1, 48)),
                ]);
            }
        }

        $this->command->info('Created sample activities for users and admins');
    }
}
