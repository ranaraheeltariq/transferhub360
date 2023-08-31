<?php

namespace App\Listeners;

use App\Events\CompanyCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class CompanyRolesCreation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CompanyCreated $event): void
    {
        $company = $event->company;
        // Super Admin Role
        $role = Role::create(['company_id' => $company->id,'guard_name' => 'admin','name' => 'super admin']);
        $role->givePermissionTo(Permission::where('guard_name','admin')->get());
        // Supervisor Role
        $role = Role::create(['company_id' => $company->id,'guard_name' => 'supervisors','name' => 'supervisor']);
        $role->givePermissionTo(Permission::where('guard_name','supervisors')->get());
        // Driver Role
        $role = Role::create(['company_id' => $company->id,'guard_name' => 'drivers','name' => 'driver']);
        $role->givePermissionTo(Permission::where('guard_name','drivers')->get());
        // Passenger Role
        $role = Role::create(['company_id' => $company->id,'guard_name' => 'passengers','name' => 'passenger']);
        $role->givePermissionTo(Permission::where('guard_name','passengers')->get());
    }
}
