<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponser;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/Users?page={number}",
     *      operationId="getUserList",
     *      tags={"CompanyUser"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Companies",
     *      description="Returns list of Companies",
     *      @OA\Parameter(
     *          name="number",
     *          description="Page Number",
     *          required=false,
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
     *              @OA\Property(property="data", type="string", example="array of User list"),
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
    public function index()
    {
        $result = $this->userRepository->getAll();
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/users/create",
     *      operationId="storeUser",
     *      tags={"CompanyUser"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New User",
     *      description="Returns User data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name","email","password"},
     *                  @OA\Property(property="full_name", type="string", format="name", example="Tour Campaign"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="abc@xyz.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="password", type="password", format="password", example="password"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of User data"),
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
        $data = $request->only('full_name', 'email', 'contact_number', 'thumbnail', 'password');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_number' => 'nullable|string|max:255',
            'password'      => 'required|string|min:8',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->userRepository->create($data);
        if($result){
            $result['oauth'] = base64_encode($password);
            $result['password'] = $password;
           // Mail::to($data['email'])->send(new UserLoginDetails($result));
            return $this->successResponse($result, __('response_messages.User.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages._common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/users/detail/{id}",
     *      operationId="getUserById",
     *      tags={"CompanyUser"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of User By Id",
     *      description="Get User Data by User Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Compnay Id",
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
    public function show($id)
    {
        $result = $this->userRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/users/update/{id}",
     *      operationId="updateuser",
     *      tags={"CompanyUser"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update User",
     *      description="Returns User data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Compnay Id",
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
     *                  required={"name","email","password"},
     *                  @OA\Property(property="full_name", type="string", format="name", example="Tour Campaign"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="abc@xyz.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="password", type="password", format="password", example="password"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User Successfully Updated"),
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
     *          response=203,
     *           description="Validation Error response",
     *           @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *               @OA\Property(property="message", type="string", example="Validation error Message")
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
        $data = $request->only('full_name', 'email', 'contact_number', 'thumbnail', 'password');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,'.$id,
            'contact_number' => 'nullable|string|max:255',
            'password'      => 'required|string|min:8',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->userRepository->update($id,$data);
        if($result){
            return $this->successResponse($result, __('response_messages.User.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/users/delete/{id}",
     *      operationId="destoryUser",
     *      tags={"CompanyUser"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove User",
     *      description="Remove User by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="User Id",
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
     *              @OA\Property(property="data", type="string", example="Admin User Deleted Successfully"),
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
        $Owner = $this->userRepository->delete($id);
        if($Owner)
        {
            return $this->successResponse(null, __('response_messages.User.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
