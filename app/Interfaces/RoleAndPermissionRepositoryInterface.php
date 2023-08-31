<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface RoleAndPermissionRepositoryInterface
{
    public function permissions();
    public function permissionsByGuardName($guard);
    public function roles();
    public function rolesName();
    public function roleCreate($data);
    public function rolesPermissions(Request $request);
    public function roleUpdate($data, $role);
    public function delete($role);
    public function permissionsAssignToRole($data);
    public function getGuardName();
    public function getRolesByGuardName($guardName);
    public function countByRole($role);
}