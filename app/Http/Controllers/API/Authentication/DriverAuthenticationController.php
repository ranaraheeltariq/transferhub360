<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DriverRepository;

class DriverAuthenticationController extends Controller
{
    use ApiResponser;
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    /**
     * @OA\Post(
     * path="/api/driver/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="driverLogin",
     * tags={"DriverAuth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="email", format="email", example="driver@yopmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *      )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully Login",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="Driver Successfully Login"),
     *      @OA\Property(property="data", type="string", example="array of driver data"),
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
            'email' => 'required|string|max:255|exists:drivers',
            'password' => 'required|string',
         ]);
         if($validator->fails()){
             return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
         }
        if(Auth::guard('drivers')->attempt($request->only('email', 'password'))){
            $user = Auth::guard('drivers')->user();
            $user->tokens()->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            $user['authorisation'] = ([
                  'token' => $token,
                  'type' => 'bearer',
            ]);
            $user['role'] = 'Driver';
            return $this->successResponse($user, __('response_messages.auth.login'));
         }
         return $this->errorResponse(__('response_messages.auth.credentialsIncorrect'), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }

   /**
     * @OA\Post(
     * path="/api/driver/users/logout",
     * summary="Sign out",
     * description="Logout the User",
     * operationId="driverLogout",
     * tags={"DriverAuth"},
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
    //   Auth::guard('drivers')->user()->currentAccessToken()->delete(); //user data not readable with Guard need to fix
      Auth::user()->currentAccessToken()->delete();
      return $this->successResponse(null, __('response_messages.auth.logout'));
    }

    /**
     * @OA\Post(
     *      path="/api/driver/users/reset-password",
     *      operationId="DriverresetPassword",
     *      tags={"DriverAuth"},
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
        // $user = Auth::guard('drivers')->user(); //user data not readable with Guard need to fix
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
        $result = $this->driverRepository->passwordReset($request);
        if($result){
            return $this->successResponse(null, __('response_messages.auth.newPassword'));
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Post(
     *      path="/api/driver/users/profileupdate",
     *      operationId="updateDriverProfile",
     *      tags={"DriverAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Driver User Profile",
     *      description="Returns Driver User data",
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
     *              @OA\Property(property="message", type="string", example="Driver User Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Driver User data"),
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
        // $id = Auth::guard('drivers')->user()->id; //user data not readable with Guard need to fix
        $id = Auth::user()->id;
        $data = $request->only('full_name', 'contact_number', 'email', 'thumbnail', 'address', 'gender');
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers,email,'.$id,
            'contact_number' => 'required|string|max:255|unique:drivers,contact_number,'.$id,
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->driverRepository->update($id,$data);
        if($result){
            $result = $this->driverRepository->getById($id);
            return $this->successResponse($result, __('response_messages.driver.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}

