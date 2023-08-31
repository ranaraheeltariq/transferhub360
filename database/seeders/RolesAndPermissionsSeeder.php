<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Super Admin Role
        $arrayOfPermissionNames = ['user list','user store','user detail','user update','user delete','user password generation','driver list','driver store','driver detail','driver update','driver delete','driver password generation','driver count','uetds city list','uetds city store','uetds city detail','uetds city update','uetds city delete','supervisor list','supervisor store','supervisor detail','supervisor update','supervisor delete','supervisor password generation','vehicle list','vehicle store','vehicle detail','vehicle update','vehicle delete','vehicle count','customer list','customer store','customer detail','customer update','customer delete','flight list','flight store','flight detail','flight update','flight delete','passenger list','passenger store','passenger detail','passenger update','passenger delete','passenger password generation','hotel list','hotel store','hotel detail','hotel update','hotel delete','contact person list','contact person store','contact person detail','contact person update','contact person delete','transfer list','transfer store','transfer detail','transfer update','transfer delete','transfer assign vehicle','transfer unassign vehicle','transfer passenger store','transfer uetds file','transfer count','transfer passenger detail','permissions list','roles list','roles store','roles update','roles delete'];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'admin'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create(['company_id' => 1,'guard_name' => 'admin','name' => 'super admin']);
        $role->givePermissionTo(Permission::all());

        // Supervisor Role
        $arrayOfPermissionNames = ['transfer list','transfer detail','vehicle list','vehicle detail','driver list','driver detail','passenger list','passenger detail','transfer unassign vehicle','transfer assign vehicle','driver count','vehicle count','transfer count','transfer uetds file','transfer passenger detail'];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'supervisors'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create(['company_id' => 1,'guard_name' => 'supervisors','name' => 'supervisor']);
        $role->givePermissionTo(Permission::where('guard_name','supervisors')->get());

        // Driver Role
        $arrayOfPermissionNames = ['assigned transfer list','driver detail','transfer detail','transfer passenger detail','vehicle detail','assigned transfer update','transfer uetds file','vehicle list','transfer count'];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'drivers'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create(['company_id' => 1,'guard_name' => 'drivers','name' => 'driver']);
        $role->givePermissionTo(Permission::where('guard_name','drivers')->get());

        // Passenger Role
        $arrayOfPermissionNames = ['my transfer list'];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'passengers'];
        });
        Permission::insert($permissions->toArray());
         $role = Role::create(['company_id' => 1,'guard_name' => 'passengers','name' => 'passenger']);
         $role->givePermissionTo(Permission::where('guard_name','passengers')->get());

          // Owner Role
          $arrayOfPermissionNames = ['owner list','owner store','owner detail','owner update','owner delete','owner password generation','user list','user store','user detail','user update','user delete','user password generation','company list','company store','company detail','company update','company delete','permissions list','roles list','roles store','roles update','roles delete'];
          $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
              return ['name' => $permission, 'guard_name' => 'owners'];
          });
          Permission::insert($permissions->toArray());
           $role = Role::create(['guard_name' => 'owners','name' => 'owner']);
           $role->givePermissionTo(Permission::where('guard_name','owners')->get());
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
