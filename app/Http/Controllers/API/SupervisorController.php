<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\SupervisorRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SupervisorController extends Controller
{
    use ApiResponser;

    private SupervisorRepository $supervisorRepository;

    public function __construct(SupervisorRepository $supervisorRepository)
    {
        $this->supervisorRepository = $supervisorRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/supervisors",
     *      operationId="getSupervisorList",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Supervisors",
     *      description="Returns list of Supervisors",
     *      @OA\Parameter(
     *          name="pagination",
     *          in="query",
     *          description="pagination",
     *          required=false,
     *          @OA\Schema(type="boolean")
     *      ),
     *      @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          description="perPage",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="limit",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="start",
     *          in="query",
     *          description="start Date",
     *          required=false,
     *          @OA\Schema(type="date")
     *      ),
     *      @OA\Parameter(
     *          name="end",
     *          in="query",
     *          description="end Date",
     *          required=false,
     *          @OA\Schema(type="date")
     *      ),
     *      @OA\Parameter(
     *          name="date_column",
     *          in="query",
     *          description="Add Date Column where you want to filter by Date or between two dates",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="relational_id",
     *          in="query",
     *          description="Relational Model id",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="relational_column",
     *          in="query",
     *          description="Add Relational Data Column name for getting by specific model Id",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="order",
     *          in="query",
     *          description="order",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          enum={"asc","desc"},
     *          default="desc"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="order_column",
     *          in="query",
     *          description="Add Column where you want to display by some order",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="filter_operator",
     *          in="query",
     *          description="add Conditional Operator",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          enum={"and","or"},
     *          default="and"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search_text",
     *          in="query",
     *          description="and string for searching in all columns",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Supervisors list"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $result = $this->supervisorRepository->getAll($request);
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/supervisors/create",
     *      operationId="storeSupervisor",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Supervisor",
     *      description="Returns Supervisor data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"full_name", "contact_number", "email", "password", "address", "gender", "status"},
     *                  @OA\Property(property="id_number", type="string", format="id_number", example="3663558DDSF"),
     *                  @OA\Property(property="full_name", type="string", format="full_name", example="Ahmet Ali"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="supervisor@yopmail.com"),
     *                  @OA\Property(property="password", type="password", format="password", example="dfdf323"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
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
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="!Something went wrong please try again later."),
     *          )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->only('full_name', 'contact_number', 'email', 'password', 'thumbnail', 'address', 'gender', 'id_number', 'status');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255|unique:supervisors',
            'email' => 'required|string|email|max:255|unique:supervisors',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'id_number ' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Deactive',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->supervisorRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.supervisor.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/supervisors/detail/{id}",
     *      operationId="getSupervisorById",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Supervisor By Id",
     *      description="Get Supervisor Data by Supervisor Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Supervisor Id",
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
     *              @OA\Property(property="data", type="string", example="array of Supervisor"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
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
     *      )
     * )
     */
    public function show($id)
    {
        $result = $this->supervisorRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/supervisors/update/{id}",
     *      operationId="updateSupervisor",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Supervisor",
     *      description="Returns Supervisor data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Supervisor Id",
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
     *                  required={"full_name", "contact_number", "email", "password", "address", "gender", "status"},
     *                  @OA\Property(property="id_number", type="string", format="id_number", example="3663558DDSF"),
     *                  @OA\Property(property="full_name", type="string", format="full_name", example="Ahmet Ali"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="supervisor@yopmail.com"),
     *                  @OA\Property(property="password", type="password", format="password", example="dfdf323"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Supervisor Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Supervisor data"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
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
    public function update(Request $request, $id)
    {
        $data = $request->only('full_name', 'contact_number', 'email', 'password', 'thumbnail', 'address', 'gender', 'id_number', 'status');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255|unique:supervisors,contact_number,'.$id,
            'email' => 'required|string|email|max:255|unique:supervisors,email,'.$id,
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'id_number ' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Deactive',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->supervisorRepository->update($id,$data);
        if($result){
            $result = $this->supervisorRepository->getById($id);
            return $this->successResponse($result, __('response_messages.supervisor.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/supervisors/delete/{id}",
     *      operationId="destorySupervisor",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Supervisor",
     *      description="Remove Supervisor by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Supervisor Id",
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
     *              @OA\Property(property="message", type="string", example="Supervisor Deleted Successfully"),
     *              @OA\Property(property="data", type="string", example=null),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
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
     *      )
     * )
     */
    public function destroy($id)
    {
        $result = $this->supervisorRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.supervisor.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/supervisors/generatepassword/{id}",
     *      operationId="generatePasswordSupervisorsById",
     *      tags={"CompanySupervisor"},
     *      security={ {"bearerAuth":{} }},
     *      summary="generate Password Supervisor By Id",
     *      description="Generate Password Data by Supervisor Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Supervisor Id",
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
     *              @OA\Property(property="message", type="string", example="Password Successfully Generated"),
     *              @OA\Property(property="data", type="string", example="array of User Data"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
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
     *      )
     * )
     */
    public function generatePassword($id)
    {
        $result = $this->supervisorRepository->generatePassword($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
