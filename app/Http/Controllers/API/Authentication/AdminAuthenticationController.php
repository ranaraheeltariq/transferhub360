<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AdminAuthenticationController extends Controller
{
    use ApiResponser;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     * path="/api/companies/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="CompanyAuthLogin",
     * tags={"CompanyAuth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="email", format="email", example="admin@yopmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *      )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully Login",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="User Successfully Login"),
     *      @OA\Property(property="data", type="string", example="array of user data"),
     *        )
     *     ),
     * @OA\Response(
     *    response=203,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="The provided credentials are incorrect")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
         //Validate data
         $data = $request->only('email', 'password');
         $validator = Validator::make($data, [
            'email' => 'required|string|max:255|exists:users',
            'password' => 'required|string',
         ]);
         if($validator->fails()){
             return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
         }
        if(Auth::guard('admin')->attempt($request->only('email', 'password'))){
            $user = Auth::guard('admin')->user();
            $user->tokens()->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            $user['authorisation'] = ([
                  'token' => $token,
                  'type' => 'bearer',
            ]);
            $user['role'] = 'Admin';
            $user->company;
            return $this->successResponse($user, __('response_messages.auth.login'));
         }
         return $this->errorResponse(__('response_messages.auth.credentialsIncorrect'), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }

   /**
     * @OA\Post(
     * path="/api/companies/users/logout",
     * summary="Sign out",
     * description="Logout the User",
     * operationId="CompanyAuthLogout",
     * tags={"CompanyAuth"},
     * security={ {"bearerAuth":{} }},
     * @OA\Response(
     *    response=200,
     *    description="Successfully Logout",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="Successfully logged out"),
     *      @OA\Property(property="data", type="string", example=null),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     *   )
     * )
     */
    public function logout(Request $request)
    {
    //   Auth::guard('admin')->user()->currentAccessToken()->delete(); //user data not readable with Guard need to fix
      Auth::user()->currentAccessToken()->delete();
      return $this->successResponse(null, __('response_messages.auth.logout'));
    }

    /**
     * @OA\Post(
     *      path="/api/companies/users/reset-password",
     *      operationId="CompanyAuthresetPassword",
     *      tags={"CompanyAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Reset Password of login User",
     *      description="Returns Success Message",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"current_password","password","password_confirmation"},
     *                  @OA\Property(property="current_password", type="string", format="current_password", example=""),
     *                  @OA\Property(property="password", type="string", format="password", example=""),
     *                  @OA\Property(property="password_confirmation", type="string", format="password_confirmation", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="New Password Successfully Created"),
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
    public function passwordReset(Request $request)
    {
        // $user = Auth::guard('admin')->user(); //user data not readable with Guard need to fix
        $user = Auth::user();
        return $this->successResponse($user, __('response_messages.auth.newPassword'));
        //Validate data
        $data = $request->only('current_password', 'password','password_confirmation');
        $validator = Validator::make($data, [
           'current_password' => 'required|string',
           'password' => 'required|min:8|confirmed',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        if (! $user || ! Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse(['current_password' => [__('response_messages.auth.passwordIncorrect')]], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->userRepository->passwordReset($request);
        if($result){
            return $this->successResponse($result, __('response_messages.auth.newPassword'));
        }
        return $this->successResponse($result);
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/users/profileupdate",
     *      operationId="CompanyAuthupdateUserProfile",
     *      tags={"CompanyAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update User Profile",
     *      description="Returns Company User data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"full_name","email"},
     *                  @OA\Property(property="full_name", type="string", format="full_name", example="John san"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="abc@xyz.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example="")
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Company User Successfully Updated"),
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
    public function update(Request $request)
    {
        // $id = Auth::guard('admin')->user()->id; //user data not readable with Guard need to fix
        $id = Auth::user()->id;
        $data = $request->only('full_name', 'email', 'contact_number', 'thumbnail');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'contact_number' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->userRepository->update($id,$data);
        if($result){
            $result = $this->userRepository->getById($id);
            return $this->successResponse($result, __('response_messages.user.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
    * @OA\Get(
    *      path="/api/companies/users/profile",
    *      operationId="getCompanyUserProfile",
    *      tags={"CompanyAuth"},
    *      security={ {"bearerAuth":{} }},
    *      summary="Get User Profile",
    *      description="Returns Admin Profile data",
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="string", example="success"),
    *              @OA\Property(property="message", type="string", example=null),
    *              @OA\Property(property="data", type="string", example="array of Admin User data"),
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
   public function profile()
   {
       $result = $this->userRepository->profile();
       if($result){
           return $this->successResponse($result);
       }
       return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
   }

    /**
     * @OA\Post(
     *      path="/api/companies/users/device_token",
     *      operationId="CompanyDeviceTokenUpdate",
     *      tags={"CompanyAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update User device token",
     *      description="Returns Company User data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"device_token"},
     *                  @OA\Property(property="device_token", type="string", format="device_token", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Device Token Successfully Updated"),
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
     *          response=404,
     *          description="Page Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Page Not Found"),
     *          )
     *      )
     * )
     */
    public function deviceTokenUpdate(Request $request)
    {
        $data = $request->only('device_token');
        $validator = Validator::make($data,[
            'device_token' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->userRepository->deviceTokenUpdate($data);
        if($result){
            return $this->successResponse($result, __('response_messages.common.deviceToken'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
