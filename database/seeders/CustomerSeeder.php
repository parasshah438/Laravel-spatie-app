<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create customer-specific permissions
        $customerPermissions = [
            'view-own-orders',
            'download-invoices', 
            'update-own-profile',
            'contact-support',
            'view-own-activities',
        ];

        foreach ($customerPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'customer'
            ]);
        }

        // Create customer role
        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'customer'
        ]);

        // Assign permissions to customer role
        $customerRole->givePermissionTo($customerPermissions);

        // Create sample customers
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john@company.com',
                'company' => 'Smith & Associates',
                'phone' => '+1-555-0101',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@techcorp.com',
                'company' => 'TechCorp Solutions',
                'phone' => '+1-555-0102',
                'city' => 'San Francisco',
                'state' => 'CA', 
                'country' => 'USA',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'mike@retail.com',
                'company' => 'Brown Retail Group',
                'phone' => '+1-555-0103',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@marketing.com',
                'company' => 'Wilson Marketing',
                'phone' => '+1-555-0104',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
            ],
            [
                'name' => 'David Lee',
                'email' => 'david@consulting.com',
                'company' => 'Lee Consulting',
                'phone' => '+1-555-0105',
                'city' => 'Austin',
                'state' => 'TX',
                'country' => 'USA',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('password123'), // Default password
                'company' => $customerData['company'],
                'phone' => $customerData['phone'],
                'city' => $customerData['city'],
                'state' => $customerData['state'],
                'country' => $customerData['country'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Assign customer role
            $customer->assignRole('customer');
        }

        $this->command->info('Created 5 sample customers with customer role');
    }
}
