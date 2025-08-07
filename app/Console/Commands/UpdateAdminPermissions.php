<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UpdateAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update admin role with user management permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'admin')->first();
        
        if ($adminRole) {
            $adminRole->givePermissionTo(['user-list', 'user-create', 'user-edit', 'user-delete']);
            $this->info('Admin role updated with user management permissions');
        } else {
            $this->error('Admin role not found');
        }
        
        return 0;
    }
}
