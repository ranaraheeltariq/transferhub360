<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\PassengerRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PassengerController extends Controller
{
    use ApiResponser;

    private PassengerRepository $passengerRepository;

    public function __construct(PassengerRepository $passengerRepository)
    {
        $this->passengerRepository = $passengerRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/passengers?page={number}",
     *      operationId="getPassengerList",
     *      tags={"CompanyPassenger"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Passengers",
     *      description="Returns list of Passengers",
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
     *              @OA\Property(property="data", type="string", example="array of Passengers list"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *     )
     * )
     */
    public function index()
    {
        $result = $this->passengerRepository->getAll();
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/passengers/create",
     *      operationId="storePassenger",
     *      tags={"CompanyPassenger"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Passenger",
     *      description="Returns Passenger data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"customer_id","first_name", "last_name", "gender", "status"},
     *                  @OA\Property(property="customer_id", type="customers", format="customer_id", example="1"),
     *                  @OA\Property(property="first_name", type="string", format="first_name", example="Ahmet"),
     *                  @OA\Property(property="last_name", type="string", format="last_name", example="Ali"),
     *                  @OA\Property(property="email", type="email", format="email", example="passenger@yopmail.com"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="password", type="password", format="password", example="password"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *                  @OA\Property(property="nationality", type="string", format="nationality", example="UK"),
     *                  @OA\Property(property="age", type="integer", format="age", example="20"),
     *                  @OA\Property(property="id_number", type="string", format="id_number", example="DR788995"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Passenger Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Passenger Data"),
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
        $data = $request->only('customer_id', 'first_name', 'last_name', 'email', 'contact_number', 'password', 'thumbnail', 'gender', 'nationality', 'age', 'id_number', 'status');
        $validator = Validator::make($data, [
            'customer_id' => 'required|string|max:255|exists:customers,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:passengers',
            'contact_number' => 'nullable|string|max:255|unique:passengers',
            'password'      => 'nullable|string|min:8',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'gender' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'id_number' => 'nullable|string|max:255|unique:passengers',
            'status' => 'required|in:Active,Deactive',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->passengerRepository->create($data);
        if($result){
            $result['oauth'] = base64_encode($request->password);
            $result['password'] = $request->password;
           // Mail::to($data['email'])->send(new UserLoginDetails($result));
            return $this->successResponse($result, __('response_messages.passenger.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/passengers/detail/{id}",
     *      operationId="getpassengerById",
     *      tags={"CompanyPassenger"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Passenger By Id",
     *      description="Get Passenger Data by Passenger Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Passenger Id",
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
     *              @OA\Property(property="data", type="string", example="array of Passenger"),
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
     *      )
     * )
     */
    public function show($id)
    {
        $result = $this->passengerRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/passengers/update/{id}",
     *      operationId="updatePassenger",
     *      tags={"CompanyPassenger"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Passenger",
     *      description="Returns Passenger data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Passenger Id",
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
     *                  required={"customer_id","first_name", "last_name", "gender", "status"},
     *                  @OA\Property(property="customer_id", type="customers", format="customer_id", example="1"),
     *                  @OA\Property(property="first_name", type="string", format="first_name", example="Ahmet"),
     *                  @OA\Property(property="last_name", type="string", format="last_name", example="Ali"),
     *                  @OA\Property(property="email", type="email", format="email", example="passenger@yopmail.com"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="gender", type="string", format="gender", example="Male"),
     *                  @OA\Property(property="nationality", type="string", format="nationality", example="UK"),
     *                  @OA\Property(property="age", type="integer", format="age", example="20"),
     *                  @OA\Property(property="id_number", type="string", format="id_number", example="DR788995"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Passenger Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Passenger data"),
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
        $data = $request->only('customer_id', 'first_name', 'last_name', 'email', 'contact_number', 'thumbnail', 'gender', 'nationality', 'age', 'id_number', 'status');
        $validator = Validator::make($data, [
            'customer_id' => 'required|string|max:255|exists:customers,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:passengers,email,'.$id,
            'contact_number' => 'nullable|string|max:255|unique:passengers,contact_number,'.$id,
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'gender' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'id_number' => 'nullable|string|max:255|unique:passengers,id_number,'.$id,
            'status' => 'required|in:Active,Deactive',
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
     * @OA\Post(
     *      path="/api/companies/passengers/delete/{id}",
     *      operationId="destoryPassenger",
     *      tags={"CompanyPassenger"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Passenger",
     *      description="Remove Passenger by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Passenger Id",
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
     *              @OA\Property(property="message", type="string", example="Passenger Deleted Successfully"),
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
        $result = $this->passengerRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.passenger.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
