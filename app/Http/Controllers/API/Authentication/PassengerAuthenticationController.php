<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\Passenger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PassengerRepository;

class PassengerAuthenticationController extends Controller
{
    use ApiResponser;
    private PassengerRepository $passengerRepository;

    public function __construct(PassengerRepository $passengerRepository)
    {
        $this->passengerRepository = $passengerRepository;
    }

    /**
     * @OA\Post(
     * path="/api/passenger/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="passengerLogin",
     * tags={"PassengerAuth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="email", format="email", example="passenger@yopmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *      )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully Login",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="Passenger Successfully Login"),
     *      @OA\Property(property="data", type="string", example="array of passenger data"),
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
            'email' => 'required|string|max:255|exists:passengers',
            'password' => 'required|string',
         ]);
         if($validator->fails()){
             return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
         }
        if(Auth::guard('passengers')->attempt($request->only('email', 'password'))){
            $user = Auth::guard('passengers')->user();
            $user->tokens()->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            $user['authorisation'] = ([
                  'token' => $token,
                  'type' => 'bearer',
            ]);
            $user['role'] = 'Passenger';
            return $this->successResponse($user, __('response_messages.auth.login'));
         }
         return $this->errorResponse(__('response_messages.auth.credentialsIncorrect'), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }

   /**
     * @OA\Post(
     * path="/api/passenger/users/logout",
     * summary="Sign out",
     * description="Logout the passenger",
     * operationId="passengerLogout",
     * tags={"PassengerAuth"},
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
    //   Auth::guard('passengers')->user()->currentAccessToken()->delete(); //user data not readable with Guard need to fix
      Auth::user()->currentAccessToken()->delete();
      return $this->successResponse(null, __('response_messages.auth.logout'));
    }

    /**
     * @OA\Post(
     *      path="/api/passenger/users/reset-password",
     *      operationId="PassengerresetPassword",
     *      tags={"PassengerAuth"},
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
        // $user = Auth::guard('passengers')->user(); //user data not readable with Guard need to fix
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
        $result = $this->passengerRepository->passwordReset($request);
        if($result){
            return $this->successResponse(null, __('response_messages.auth.newPassword'));
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Post(
     *      path="/api/passenger/users/profileupdate",
     *      operationId="updatePassengerProfile",
     *      tags={"PassengerAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Passenger User Profile",
     *      description="Returns Passenger User data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"first_name", "last_name", "gender"},
     *                  @OA\Property(property="first_name", type="string", format="first_name", example="Ahmet"),
     *                  @OA\Property(property="last_name", type="string", format="last_name", example="Ali"),
     *                  @OA\Property(property="email", type="email", format="email", example="driver@yopmail.com"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *                  @OA\Property(property="nationality", type="string", format="nationality", example="United Kingdom"),
     *                  @OA\Property(property="country_code", type="string", format="country_code", example="UK"),
     *                  @OA\Property(property="age", type="integer", format="age", example="20"),
     *                  @OA\Property(property="id_number", type="string", format="id_number", example="DR788995"),
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
        // $id = Auth::guard('passengers')->user()->id; //user data not readable with Guard need to fix
        $id = Auth::user()->id;
        $data = $request->only('first_name', 'last_name', 'email', 'contact_number', 'thumbnail', 'gender', 'nationality', 'country_code', 'age', 'id_number');
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:passengers,email,'.$id,
            'contact_number' => 'nullable|string|max:255|unique:passengers,contact_number,'.$id,
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'gender' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'id_number' => 'nullable|string|max:255|unique:passengers,id_number,'.$id,
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->passengerRepository->update($id,$data);
        if($result){
            $result = $this->passengerRepository->getById($id);
            return $this->successResponse($result, __('response_messages.passenger.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/api/passenger/users/profile",
     *      operationId="getPassengerProfile",
     *      tags={"PassengerAuth"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get Passenger Profile",
     *      description="Returns Passenger Profile data",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example=null),
     *              @OA\Property(property="data", type="string", example="array of Passenger data"),
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
        $result = $this->ownerRepository->profile();
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
