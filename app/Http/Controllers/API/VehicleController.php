<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    use ApiResponser;

    private VehicleRepository $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/vehicles?page={number}",
     *      operationId="getVehiclesList",
     *      tags={"CompanyVehicle"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Vehicles",
     *      description="Returns list of Vehicles",
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
     *              @OA\Property(property="data", type="string", example="array of Vehicles list"),
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
        $result = $this->vehicleRepository->getAll();
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/vehicles/create",
     *      operationId="storeVehicle",
     *      tags={"CompanyVehicle"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Vehicle",
     *      description="Returns Vehicle data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"driver_id","number_plate","name","modal"},
     *                  @OA\Property(property="driver_id", type="integer", format="driver_id", example="1"),
     *                  @OA\Property(property="number_plate", type="string", format="number_plate", example="ABD323"),
     *                  @OA\Property(property="name", type="string", format="name", example="John Pual"),
     *                  @OA\Property(property="modal", type="string", format="modal", example="Honda City"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Vehicle Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Vehicle data"),
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
        $data = $request->only('driver_id','number_plate','name','modal','thumbnail');
        $validator = Validator::make($data, [
            'driver_id' => 'required|exists:drivers,id',
            'number_plate' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'modal' => 'required|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->vehicleRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.vehicle.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/vehicles/detail/{id}",
     *      operationId="getVehicleById",
     *      tags={"CompanyVehicle"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Vehicle By Id",
     *      description="Get Vehicle Data by Vehicle Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Vehicle Id",
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
     *              @OA\Property(property="data", type="string", example="array of Vehicle Data"),
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
        $result = $this->vehicleRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/vehicles/update/{id}",
     *      operationId="updateVehicle",
     *      tags={"CompanyVehicle"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Vehicle",
     *      description="Returns Vehicle data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Vehicle Id",
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
     *                  required={"driver_id","number_plate","name","modal"},
     *                  @OA\Property(property="driver_id", type="integer", format="driver_id", example="1"),
     *                  @OA\Property(property="number_plate", type="string", format="number_plate", example="ABD323"),
     *                  @OA\Property(property="name", type="string", format="name", example="John Pual"),
     *                  @OA\Property(property="modal", type="string", format="modal", example="Honda City"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Vehicle Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Vehicle Data"),
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
        $data = $request->only('driver_id','number_plate','name','modal','thumbnail');
        $validator = Validator::make($data, [
            'driver_id' => 'required|exists:drivers,id',
            'number_plate' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'modal' => 'required|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->vehicleRepository->update($id,$data);
        if($result){
            $result = $this->vehicleRepository->getById($id);
            return $this->successResponse($result, __('response_messages.vehicle.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/vehicles/delete/{id}",
     *      operationId="destoryVehicle",
     *      tags={"CompanyVehicle"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Vehicle",
     *      description="Remove Vehicle by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Vehicle Id",
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
     *              @OA\Property(property="message", type="string", example="Vehicle Deleted Successfully"),
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
        $result = $this->vehicleRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.vehicle.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
