<?php

namespace App\Repositories;

use App\Interfaces\RoleAndPermissionRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionRepository implements RoleAndPermissionRepositoryInterface
{

    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissions()
    {
        $permissions = Permission::all();
        return $permissions;
    }

    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissionsByGuardName($guard)
    {
        $permissions = Permission::where('guard_name', $guard)->get();
        return $permissions;
    }

    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return $roles;
    }

    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function rolesName()
    {
        $roles = Role::all()->pluck('name');
        return $roles;
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  $data
     * @return \App\Model\Role $role
     */
    public function roleCreate($data)
    {

        $permissions = $data['permissions'];
        unset($data['permissions']);
        $data['name'] = $data['role'];
        unset($data['role']);
        $role = Role::create($data);
        if($role) {
            $role->syncPermissions($permissions);
            \Artisan::call('cache:clear');
            return $role;
        }
        return false;
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rolesPermissions(Request $request)
    {
        $role = Role::whereIn('id',$request->id)->with(['permissions'])->get();
        if($role){
            return $role;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array  $data
     * @param  App\Models\Role  $role
     * @return App\Models\Role  $role
     */
    public function roleUpdate($data, $role)
    {
        $role = Role::findOrFail($role);
        if($role)
        {
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $status = $role->update($data);
            if($status)
            {
                $role->syncPermissions($permissions);
            }
            \Artisan::call('cache:clear');
            return $role;
        }
        return false;
    }

    /**
    * Remove the specified resource in storage.
    *
    * @param  Spatie\Permission\Models\Role  $role
    * @return \Illuminate\Http\Response
    */
   public function delete($role)
   {
       $role = Role::find($role);
       if($role)
       {
           $role->syncPermissions([]);
           $role->delete();
           \Artisan::call('cache:clear');
           return $role;
       }
       return false;
   }

   /**
    * Assign the Permissions to Role.
    *
    * @param  Spatie\Permission\Models\Role  $role
    * @return \Illuminate\Http\Response
    */
   public function permissionsAssignToRole($data)
    {
       $role = Role::findOrFail($data['role']);
       if($role) {
           $role->givePermissionTo($data['permission']);
           \Artisan::call('cache:clear');
           return $role;
       }
       return false;
    }

    /**
     * Get Available Guard Name.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGuardName()
    {
        $guards = Role::selectRaw('guard_name')->groupBy('guard_name')->get();
        return $guards;
    }

    /**
    * Get Roles By Guard Name.
    *
    * @return \Illuminate\Http\Response
    */
    public function getRolesByGuardName($guardName)
    {
        $roles = Role::where('guard_name', $guardName)->get();
        return $roles;
    }

    /**
    * Get the specified resource in storage.
    *
    * @return \Illuminate\Http\Response
    */
    public function countByRole($role)
    {
        $user = User::role($role)->count();
        return $user;
    }

}