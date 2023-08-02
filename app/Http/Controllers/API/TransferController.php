<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\TransferRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    use ApiResponser;

    private TransferRepository $transferRepository;

    public function __construct(TransferRepository $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/transfers?page={number}",
     *      operationId="getTransfersList",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Transfers",
     *      description="Returns list of Transfers",
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
     *              @OA\Property(property="data", type="string", example="array of Transfers list"),
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
        $result = $this->transferRepository->getAll();
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/create",
     *      operationId="storeTransfer",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Transfer",
     *      description="Returns Transfer data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"customer_id","pickup_location","dropoff_location","pickup_date","pickup_time","pickup_country_code","pickup_country","pickup_city_code","pickup_city","pickup_zone_code","pickup_zone","dropoff_country_code","dropoff_country","dropoff_city_code","dropoff_city","dropoff_zone_code","dropoff_zone","status"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example="1"),
     *                  @OA\Property(property="vehicle_id", type="integer", format="vehicle_id", example="1"),
     *                  @OA\Property(property="driver_id", type="integer", format="driver_id", example="1"),
     *                  @OA\Property(property="pickup_location", type="string", format="pickup_location", example="IST Airport"),
     *                  @OA\Property(property="dropoff_location", type="string", format="dropoff_location", example="EST Hospital Umraniya"),
     *                  @OA\Property(property="pickup_date", type="date", format="pickup_date", example="2022-07-22"),
     *                  @OA\Property(property="pickup_time", type="time", format="pickup_time", example="20:10"),
     *                  @OA\Property(property="pickup_country_code", type="string", format="pickup_country_code", example="TR"),
     *                  @OA\Property(property="pickup_country", type="string", format="pickup_country", example="Turkiye"),
     *                  @OA\Property(property="pickup_city_code", type="integer", format="pickup_city_code", example="34"),
     *                  @OA\Property(property="pickup_city", type="string", format="pickup_city", example="İSTANBUL"),
     *                  @OA\Property(property="pickup_zone_code", type="integer", format="pickup_zone", example="1852"),
     *                  @OA\Property(property="pickup_zone", type="string", format="pickup_zone", example="ÜMRANİYE"),
     *                  @OA\Property(property="dropoff_country_code", type="string", format="dropoff_city", example="TR"),
     *                  @OA\Property(property="dropoff_country", type="string", format="dropoff_city", example="Turkiye"),
     *                  @OA\Property(property="dropoff_city_code", type="integer", format="dropoff_city", example="34"),
     *                  @OA\Property(property="dropoff_city", type="string", format="dropoff_city", example="İSTANBUL"),
     *                  @OA\Property(property="dropoff_zone_code", type="string", format="dropoff_zone", example="1663"),
     *                  @OA\Property(property="dropoff_zone", type="string", format="dropoff_zone", example="ŞİŞLİ"),
     *                  @OA\Property(property="pickup_start_time", type="datetime", format="pickup_start_time", example="2022-07-22 20:12"),
     *                  @OA\Property(property="dropoff_time", type="datetime", format="dropoff_time", example="2022-07-22 20:52"),
     *                  @OA\Property(property="vehicle_assigned_time", type="datetime", format="vehicle_assigned_time", example="2022-07-14 20:12"),
     *                  @OA\Property(property="vehicle_assigned_by", type="string", format="vehicle_assigned_by", description="Login User Name", example="Raheel"),
     *                  @OA\Property(property="status", type="string", format="status", enum={"Pendding","Process","Completed"}, default="Pendding"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Transfer data"),
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
        $data = $request->only('customer_id','vehicle_id','driver_id','pickup_location','dropoff_location','pickup_date','pickup_time','pickup_country_code','pickup_country','pickup_city_code','pickup_city','pickup_zone_code','pickup_zone','dropoff_country_code','dropoff_country','dropoff_city_code','dropoff_city','dropoff_zone_code','dropoff_zone','pickup_start_time','dropoff_time','vehicle_assigned_time','vehicle_assigned_by','status');
        $validator = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id|exists:vehicles,driver_id',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_country_code' => 'required|string|max:255',
            'pickup_country' => 'required|string|max:255',
            'pickup_city_code' => 'required|integer',
            'pickup_city' => 'required|string|max:255',
            'pickup_zone_code' => 'required|integer',
            'pickup_zone' => 'required|string|max:255',
            'dropoff_country_code' => 'required|string|max:255',
            'dropoff_country' => 'required|string|max:255',
            'dropoff_city_code' => 'required|integer',
            'dropoff_city' => 'required|string|max:255',
            'dropoff_zone_code' => 'required|integer',
            'dropoff_zone' => 'required|string|max:255',
            'pickup_start_time' => 'nullable|date_format:Y-m-d H:i',
            'dropoff_time' => 'nullable|date_format:Y-m-d H:i',
            'vehicle_assigned_time' => 'nullable|date_format:Y-m-d H:i',
            'vehicle_assigned_by' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.transfer.created'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/transfers/detail/{id}",
     *      operationId="getTransferById",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Transfer By Id",
     *      description="Get Transfer Data by Transfer Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Id",
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
     *              @OA\Property(property="data", type="string", example="array of Transfer Data"),
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
        $result = $this->transferRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/update/{id}",
     *      operationId="updateTransfer",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Transfer",
     *      description="Returns Transfer data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Id",
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
     *                  required={"customer_id","pickup_location","dropoff_location","pickup_date","pickup_time","pickup_country_code","pickup_country","pickup_city_code","pickup_city","pickup_zone_code","pickup_zone","dropoff_country_code","dropoff_country","dropoff_city_code","dropoff_city","dropoff_zone_code","dropoff_zone","status"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example="1"),
     *                  @OA\Property(property="vehicle_id", type="integer", format="vehicle_id", example="1"),
     *                  @OA\Property(property="driver_id", type="integer", format="driver_id", example="1"),
     *                  @OA\Property(property="pickup_location", type="string", format="pickup_location", example="IST Airport"),
     *                  @OA\Property(property="dropoff_location", type="string", format="dropoff_location", example="EST Hospital Umraniya"),
     *                  @OA\Property(property="pickup_date", type="date", format="pickup_date", example="2022-07-22"),
     *                  @OA\Property(property="pickup_time", type="time", format="pickup_time", example="20:10"),
     *                  @OA\Property(property="pickup_country_code", type="string", format="pickup_country_code", example="TR"),
     *                  @OA\Property(property="pickup_country", type="string", format="pickup_country", example="Turkiye"),
     *                  @OA\Property(property="pickup_city_code", type="integer", format="pickup_city_code", example="34"),
     *                  @OA\Property(property="pickup_city", type="string", format="pickup_city", example="İSTANBUL"),
     *                  @OA\Property(property="pickup_zone_code", type="integer", format="pickup_zone", example="1852"),
     *                  @OA\Property(property="pickup_zone", type="string", format="pickup_zone", example="ÜMRANİYE"),
     *                  @OA\Property(property="dropoff_country_code", type="string", format="dropoff_city", example="TR"),
     *                  @OA\Property(property="dropoff_country", type="string", format="dropoff_city", example="Turkiye"),
     *                  @OA\Property(property="dropoff_city_code", type="integer", format="dropoff_city", example="34"),
     *                  @OA\Property(property="dropoff_city", type="string", format="dropoff_city", example="İSTANBUL"),
     *                  @OA\Property(property="dropoff_zone_code", type="string", format="dropoff_zone", example="1663"),
     *                  @OA\Property(property="dropoff_zone", type="string", format="dropoff_zone", example="ŞİŞLİ"),
     *                  @OA\Property(property="pickup_start_time", type="datetime", format="pickup_start_time", example="2022-07-22 20:12"),
     *                  @OA\Property(property="dropoff_time", type="datetime", format="dropoff_time", example="2022-07-22 20:52"),
     *                  @OA\Property(property="vehicle_assigned_time", type="datetime", format="vehicle_assigned_time", example="2022-07-14 20:12"),
     *                  @OA\Property(property="vehicle_assigned_by", type="string", format="vehicle_assigned_by", description="Login User Name", example="Raheel"),
     *                  @OA\Property(property="status", type="string", format="status", enum={"Pendding","Process","Completed"}, default="Pendding"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Transfer Data"),
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
        $data = $request->only('customer_id','vehicle_id','driver_id','pickup_location','dropoff_location','pickup_date','pickup_time','pickup_country_code','pickup_country','pickup_city_code','pickup_city','pickup_zone_code','pickup_zone','dropoff_country_code','dropoff_country','dropoff_city_code','dropoff_city','dropoff_zone_code','dropoff_zone','pickup_start_time','dropoff_time','vehicle_assigned_time','vehicle_assigned_by','status');
        $validator = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id|exists:vehicles,driver_id',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_country_code' => 'required|string|max:255',
            'pickup_country' => 'required|string|max:255',
            'pickup_city_code' => 'required|integer',
            'pickup_city' => 'required|string|max:255',
            'pickup_zone_code' => 'required|integer',
            'pickup_zone' => 'required|string|max:255',
            'dropoff_country_code' => 'required|string|max:255',
            'dropoff_country' => 'required|string|max:255',
            'dropoff_city_code' => 'required|integer',
            'dropoff_city' => 'required|string|max:255',
            'dropoff_zone_code' => 'required|integer',
            'dropoff_zone' => 'required|string|max:255',
            'pickup_start_time' => 'nullable|date_format:Y-m-d H:i',
            'dropoff_time' => 'nullable|date_format:Y-m-d H:i',
            'vehicle_assigned_time' => 'nullable|date_format:Y-m-d H:i',
            'vehicle_assigned_by' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferRepository->update($id,$data);
        if($result){
            $result = $this->transferRepository->getById($id);
            return $this->successResponse($result, __('response_messages.transfer.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/delete/{id}",
     *      operationId="destoryTransfer",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Transfer",
     *      description="Remove Transfer by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Id",
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
     *              @OA\Property(property="message", type="string", example="Transfer Deleted Successfully"),
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
        $result = $this->transferRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.transfer.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
