<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\RoleAndPermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class RolesAndPermissionController extends Controller
{
    use ApiResponser;

    private RoleAndPermissionRepository $roleAndPermissionRepository;

    public function __construct(RoleAndPermissionRepository $roleAndPermissionRepository)
    {
        $this->roleAndPermissionRepository = $roleAndPermissionRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/permissions",
     *      operationId="getPermissions",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Permissions list",
     *      description="Get Permissions Data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Permissions"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function permissions()
    {
        $permissions =  $this->roleAndPermissionRepository->permissions();
        return $this->successResponse($permissions);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/permissions/guard/{name}",
     *      operationId="getpermissionsByGuard",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Permissions By Guard Name",
     *      description="Get Permissions Data by Guard Name",
     *      @OA\Parameter(
     *          name="name",
     *          description="guard name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Permissions"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function permissionsByGuardName($guard)
    {
        $permissions = $this->roleAndPermissionRepository->permissionsByGuardName($guard);
        return $this->successResponse($permissions);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles",
     *      operationId="getRoles",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Roles",
     *      description="Get Roles Data with Permissions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Roles"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function roles()
    {
        $roles = $this->roleAndPermissionRepository->roles();
        return $this->successResponse($roles);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/rolesnamelist",
     *      operationId="getRolesNameData",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Roles",
     *      description="Get Roles Name and ID",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Roles"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function rolesName()
    {
        $roles = $this->roleAndPermissionRepository->rolesName();
        return $this->successResponse($roles);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/roles/create",
     *      operationId="storeRole",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Role",
     *      description="Returns Role data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"role", "permissions[]", "guard_name"},
     *                  @OA\Property(property="company_id", type="string", format="company_id", example="1"),
     *                  @OA\Property(property="role", type="string", format="role", example="Editor"),
     *                  @OA\Property(property="guard_name", type="in:admin,owners", format="guard_name", example="admin"),
     *                  @OA\Property(property="permissions[]", type="array", description="Permission Name", @OA\Items(type="string", format="permissions", default="transfer list")),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Supervisor Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Supervisor Data"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=203,
     *           description="Validation Error response",
     *           @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *               @OA\Property(property="message", type="string", example="Validation error Message")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="!Something went wrong please try again later."),
     *          )
     *     )
     * )
     */
    public function roleCreate(Request $request)
    {
        //Validate data
        $data = $request->only('company_id', 'role', 'permissions','guard_name');
        $company_id = $request->has('company_id') ? $request->company_id : \Auth::user()->company_id;
        $validator = Validator::make($data, [
            'company_id' => 'nullable|exists:companies,id',
            'role' => ['required','string','max:255',Rule::unique('roles','name')->where(function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })],
            'guard_name' => 'required|string|max:255|in:admin,owners',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|exists:permissions,name',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->roleAndPermissionRepository->roleCreate($data);
        if($result) {
            return $this->successResponse($result, __('response_messages.role.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/detail",
     *      operationId="detailRoles",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get Roles Detail with Permissions",
     *      description="Returns role data",
     *      @OA\Parameter(
     *          name="id[]",
     *          description="Role id",
     *          required=true,
     *          allowEmptyValue=true,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              ),
     *              uniqueItems=true,
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Roles with Permissions"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="!Something went wrong please try again later."),
     *          )
     *     )
     * )
     */
    public function show(Request $request)
    {
        $result = $this->roleAndPermissionRepository->rolesPermissions($request);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/roles/update/{id}",
     *      operationId="updateRole",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Role",
     *      description="Returns Role data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"role", "permissions[]", "guard_name"},
     *                  @OA\Property(property="company_id", type="string", format="company_id", example="1"),
     *                  @OA\Property(property="role", type="string", format="role", example="Editor"),
     *                  @OA\Property(property="guard_name", type="in:admin,owners", format="guard_name", example="admin"),
     *                  @OA\Property(property="permissions[]", type="array", description="Permission Name", @OA\Items(type="string", format="permissions", default="transfer list")),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Role Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Role data"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=203,
     *           description="Validation Error response",
     *           @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *               @OA\Property(property="message", type="string", example="Validation error Message")
     *          )
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="!Something went wrong please try again later."),
     *          )
     *     )
     * )
     */
    public function roleUpdate(Request $request, $role)
    {
        //Validate data
        $data = $request->only('company_id', 'role', 'permissions','guard_name');
        $company_id = $request->has('company_id') ? $request->company_id : \Auth::user()->company_id;
        $validator = Validator::make($data, [
            'role' => ['required','string','max:255',Rule::unique('roles','name')->where(function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->ignore($role)],
            'guard_name' => 'required|string|max:255|in:admin,owners',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|exists:permissions,name',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->roleAndPermissionRepository->roleUpdate($data, $role);
        if($result) {
            return $this->successResponse($result, __('response_messages.role.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/roles/delete/{id}",
     *      operationId="destoryRole",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Role",
     *      description="Remove Role by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Role Deleted Successfully"),
     *              @OA\Property(property="data", type="string", example=null),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        $result = $this->roleAndPermissionRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.role.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/roles/permissions/assign",
     *      operationId="assignPermissionToRoles",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Assigne Permissions to Role",
     *      description="Assigne Permissions to Role",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"role", "permission"},
     *                  @OA\Property(property="role", type="integer", format="role", example="1"),
     *                  @OA\Property(property="permission", type="string", format="permission", default="transfer list"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Permission Assigned to Role Successfully"),
     *              @OA\Property(property="data", type="string", example=null),
     *          )
     *       ),
     *      @OA\Response(
     *          response=203,
     *           description="Validation Error response",
     *           @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *               @OA\Property(property="message", type="string", example="Validation error Message")
     *          )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function permissionsAssignToRole(Request $request)
    {
        $data = $request->only('role','permission');
        $validator = Validator::make($data, [
            'role' => 'required|exists:roles,id',
            'permission' => 'required|exists:permissions,name',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->roleAndPermissionRepository->permissionsAssignToRole($data);
        if($result) {
            return $this->successResponse($result, __('response_messages.role.assigned'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/guards",
     *      operationId="getGuards",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Guards",
     *      description="Get Guards Name",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Guards"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function getGuardName()
    {
        $guards = $this->roleAndPermissionRepository->getGuardName();
        return $this->successResponse($guards);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/guard/{guard}",
     *      operationId="getRolesByGuardName",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Roles By Guard Name",
     *      description="Get Roles Data by Guard Name",
     *      @OA\Parameter(
     *          name="guard",
     *          description="guard name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Roles"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Not Correct Permissions",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *          )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
   public function getRolesByGuardName($guardName)
   {
        $result = $this->roleAndPermissionRepository->getRolesByGuardName($guardName);
        return $this->successResponse($result);
   }

    /**
     * @OA\Get(
     *      path="/api/companies/roles/count/{id}",
     *      operationId="countOfUserbyRoleId",
     *      tags={"CompanyRolesAndPermissions"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get Count of Users by Role Id",
     *      description="Returns Count of Users by Role Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="Count of User by Role Id"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="!Something went wrong please try again later."),
     *          )
     *     )
     * )
     */
   public function countByRole($role)
   {
        $result = $this->roleAndPermissionRepository->countByRole($role);
        return $this->successResponse($result);
   }
}
