<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\Supervisor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SupervisorRepository;

class SupervisorAuthenticationController extends Controller
{
    use ApiResponser;
    private SupervisorRepository $supervisorRepository;

    public function __construct(SupervisorRepository $supervisorRepository)
    {
        $this->supervisorRepository = $supervisorRepository;
    }

    /**
     * @OA\Post(
     * path="/api/supervisor/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="supervisorLogin",
     * tags={"SupervisorAuth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="email", format="email", example="supervisor@yopmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *      )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully Login",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="Supervisor Successfully Login"),
     *      @OA\Property(property="data", type="string", example="array of supervisor data"),
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
            'email' => 'required|string|max:255|exists:supervisors',
            'password' => 'required|string',
         ]);
         if($validator->fails()){
             return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
         }
        if(Auth::guard('supervisors')->attempt($request->only('email', 'password'))){
            $user = Auth::guard('supervisors')->user();
            $user->tokens()->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            $user['authorisation'] = ([
                  'token' => $token,
                  'type' => 'bearer',
            ]);
            $user->roles;
            return $this->successResponse($user, __('response_messages.auth.login'));
         }
         return $this->errorResponse(__('response_messages.auth.credentialsIncorrect'), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }

   /**
     * @OA\Post(
     * path="/api/supervisor/users/logout",
     * summary="Sign out",
     * description="Logout the User",
     * operationId="supervisorLogout",
     * tags={"SupervisorAuth"},
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
    //   Auth::guard('supervisors')->user()->currentAccessToken()->delete(); //user data not readable with Guard need to fix
      Auth::user()->currentAccessToken()->delete();
      return $this->successResponse(null, __('response_messages.auth.logout'));
    }

    /**
     * @OA\Post(
     *      path="/api/supervisor/users/reset-password",
     *      operationId="supervisorsetPassword",
     *      tags={"SupervisorAuth"},
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
     *          response=201,
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
        // $user = Auth::guard('supervisors')->user(); //user data not readable with Guard need to fix
        $user = Auth::user();
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
        $result = $this->supervisorRepository->passwordReset($request);
        if($result){
            return $this->successResponse(null, __('response_messages.auth.newPassword'));
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Post(
     *      path="/api/supervisor/users/profileupdate",
     *      operationId="updateSupervisorProfile",
     *      tags={"SupervisorAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Supervisor User Profile",
     *      description="Returns supervisor User data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"full_name","email"},
     *                  @OA\Property(property="full_name", type="string", format="full_name", example="Tour Campaign"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="abc@xyz.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Supervisor User Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Supervisor User data"),
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
        // $id = Auth::guard('supervisors')->user()->id; //user data not readable with Guard need to fix
        $id = Auth::user()->id;
        $data = $request->only('full_name', 'contact_number', 'email', 'thumbnail', 'address', 'gender');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:supervisors,email,'.$id,
            'contact_number' => 'required|string|max:255|unique:supervisors,contact_number,'.$id,
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
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
    * @OA\Get(
    *      path="/api/supervisor/users/profile",
    *      operationId="getSupervisorUserProfile",
    *      tags={"SupervisorAuth"},
    *      security={ {"bearerAuth":{} }},
    *      summary="Get Supervisor Profile",
    *      description="Returns Supervisor Profile data",
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="string", example="success"),
    *              @OA\Property(property="message", type="string", example=null),
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
    *      )
    * )
    */
   public function profile()
   {
       $result = $this->supervisorRepository->profile();
       if($result){
           return $this->successResponse($result);
       }
       return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
   }

   /**
    * @OA\Post(
    *      path="/api/supervisor/users/device_token",
    *      operationId="SupervisorDeviceTokenUpdate",
    *      tags={"SupervisorAuth"},
    *      security={ {"bearerAuth":{} }},
    *      summary="Update Supervisor device token",
    *      description="Returns Company Supervisor data",
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
       $result = $this->supervisorRepository->deviceTokenUpdate($data);
       if($result){
           return $this->successResponse($result, __('response_messages.common.deviceToken'));
       }
       return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
   }
}
