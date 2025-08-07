<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure user role exists
        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        // Create sample users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Unverified user
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Unverified user
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@user.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']], 
                $userData
            );
            
            // Assign user role to all users
            if (!$user->hasRole('user')) {
                $user->assignRole('user');
            }
        }

        echo "Created " . count($users) . " sample users with 'user' role.\n";
    }
}
