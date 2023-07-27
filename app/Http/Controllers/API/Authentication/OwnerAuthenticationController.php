<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\Owner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OwnerAuthenticationController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *          required={"username","password"},
     *          @OA\Property(property="username", type="string", format="username", example="admin@alfanovatech.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *      )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully Login",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success"),
     *      @OA\Property(property="message", type="string", example="You are successfully login"),
     *      @OA\Property(property="data", type="string", example="array of user data"),
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
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
        $data = $request->only('username', 'password');
        $validator = Validator::make($data, [
           'username' => 'required|string|max:255',
           'password' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL ) ? 'email' : 'contact_number';
        $request->merge([
           $login_type => $request->input('username')
       ]);
       if(Auth::guard('owners')->attempt($request->only($login_type, 'password'))){
           $user = Auth::guard('owners')->user();
           $user->tokens()->delete();
           $token = $user->createToken($request->username)->plainTextToken;
           $user['authorisation'] = ([
                   'token' => $token,
                   'type' => 'bearer',
           ]);
        //    $user->roles;
        //    $user->teamleader;
        //    $user->clinic->addon_subscription;
        //    $user->countries;
           return $this->successResponse($user, 'User Successfully Login');
        }
        return $this->errorResponse(['username' => ['The provided credentials are incorrect.']], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
   }
}
