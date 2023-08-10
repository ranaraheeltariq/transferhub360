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
     *      path="/api/companies/transfers",
     *      operationId="getTransfersList",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Transfers",
     *      description="Returns list of Transfers",
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
    public function index(Request $request)
    {
        $result = $this->transferRepository->getAll($request);
        return $this->successResponse($result);
    }

    /**
     * @OA\Get(
     *      path="/api/driver/transfers/mytransfers",
     *      operationId="getMyTransfersList",
     *      tags={"DriverTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Assigned Transfers",
     *      description="Returns list of Transfers",
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
    public function myTransfers(Request $request)
    {
        $result = $this->transferRepository->myTransfers($request);
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
     *                  @OA\Property(property="pickup_date", type="date", format="pickup_date", example="2023-09-22"),
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
     *                  @OA\Property(property="pickup_start_time", type="datetime", format="pickup_start_time", example="2023-09-22 20:12"),
     *                  @OA\Property(property="dropoff_time", type="datetime", format="dropoff_time", example="2023-09-22 20:52"),
     *                  @OA\Property(property="vehicle_assigned_time", type="datetime", format="vehicle_assigned_time", example="2022-07-14 20:12"),
     *                  @OA\Property(property="vehicle_assigned_by", type="string", format="vehicle_assigned_by", description="Login User Name", example="Raheel"), 
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel"),
     *                  @OA\Property(property="contact_number", type="string", format="contact_number", example="+905547778899"),
     *                  @OA\Property(property="flight_number", type="string", format="flight_number", example="PK434"),
     *                  @OA\Property(property="type", type="string", format="type", enum={"Arrival","Departure","Inner City"}, default="Inner City"),
     *                  @OA\Property(property="info", type="string", format="info", example="Deneme123"),
     *                  @OA\Property(property="file_path", type="file", format="file_path", example=""),
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
        $data = $request->only('customer_id','vehicle_id','driver_id','pickup_location','dropoff_location','pickup_date','pickup_time','pickup_country_code','pickup_country','pickup_city_code','pickup_city','pickup_zone_code','pickup_zone','dropoff_country_code','dropoff_country','dropoff_city_code','dropoff_city','dropoff_zone_code','dropoff_zone','pickup_start_time','dropoff_time','vehicle_assigned_time','vehicle_assigned_by','contact_person','contact_number','flight_number','type','info','file_path','status');
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
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'flight_number' => 'nullable|string|max:255',
            'type'          => 'nullable|string|max:255',
            'info'          => 'nullable|string',
            'file_path' => 'nullable|mimes:jpg,jpeg,jpe,png,pdf,docx|max:5120',
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
     *                  @OA\Property(property="pickup_date", type="date", format="pickup_date", example="2023-09-22"),
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
     *                  @OA\Property(property="pickup_start_time", type="datetime", format="pickup_start_time", example="2023-09-22 20:12"),
     *                  @OA\Property(property="dropoff_time", type="datetime", format="dropoff_time", example="2023-09-22 20:52"),
     *                  @OA\Property(property="vehicle_assigned_time", type="datetime", format="vehicle_assigned_time", example="2022-07-14 20:12"),
     *                  @OA\Property(property="vehicle_assigned_by", type="string", format="vehicle_assigned_by", description="Login User Name", example="Raheel"),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel"),
     *                  @OA\Property(property="contact_number", type="string", format="contact_number", example="+905547778899"),
     *                  @OA\Property(property="flight_number", type="string", format="flight_number", example="PK434"),
     *                  @OA\Property(property="type", type="string", format="type", enum={"Arrival","Departure","Inner City"}, default="Inner City"),
     *                  @OA\Property(property="info", type="string", format="info", example="Deneme123"),
     *                  @OA\Property(property="file_path", type="file", format="file_path", example=""),
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
        $data = $request->only('customer_id','vehicle_id','driver_id','pickup_location','dropoff_location','pickup_date','pickup_time','pickup_country_code','pickup_country','pickup_city_code','pickup_city','pickup_zone_code','pickup_zone','dropoff_country_code','dropoff_country','dropoff_city_code','dropoff_city','dropoff_zone_code','dropoff_zone','pickup_start_time','dropoff_time','vehicle_assigned_time','vehicle_assigned_by','contact_person','contact_number','flight_number','type','info','file_path','status');
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
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'flight_number' => 'nullable|string|max:255',
            'type'          => 'nullable|string|max:255',
            'info'          => 'nullable|string',
            'file_path' => 'nullable|mimes:jpg,jpeg,jpe,png,pdf,docx|max:5120',
            'status' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferRepository->update($id,$data);
        if($result){
            return $this->successResponse($result, __('response_messages.transfer.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/assigne/{id}",
     *      operationId="assigneTransfer",
     *      tags={"SupervisorTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="assigne Transfer",
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
     *                  required={"vehicle_id","driver_id"},
     *                  @OA\Property(property="vehicle_id", type="integer", format="vehicle_id", example="1"),
     *                  @OA\Property(property="driver_id", type="integer", format="driver_id", example="1"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Assigned"),
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
    public function assigneVehicle(Request $request, $id)
    {
        $data = $request->only('vehicle_id','driver_id');
        $validator = Validator::make($data, [
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'driver_id' => 'required|exists:vehicles,driver_id',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['assigneVehicle'] = true;
        $result = $this->transferRepository->update($id,$data);
        if($result){
            $result = $this->transferRepository->getById($id);
            return $this->successResponse($result, __('response_messages.transfer.assigned'));
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

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/delete/assigne/{id}",
     *      operationId="unassignedTransfer",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="unassigned Transfer",
     *      description="unassigned Transfer by Id",
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
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Unassigned"),
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
    public function cancelAssignedVehicle($id)
    {
        $data['unassignedVehicle'] = true;
        $result = $this->transferRepository->update($id,$data);
        if($result){
            $result = $this->transferRepository->getById($id);
            return $this->successResponse(null, __('response_messages.transfer.unassigned'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/driver/transfers/start-transfer/{id}",
     *      operationId="startTransfer",
     *      tags={"DriverTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Start Transfer by Driver",
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
     *                  required={"pickup_start_time"},
     *                  @OA\Property(property="pickup_start_time", type="datetime", format="pickup_start_time", example="2023-09-22 20:12"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Started"),
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
    public function startTransfer(Request $request, $id)
    {
        $data = $request->only('pickup_start_time');
        $validator = Validator::make($data, [
            'pickup_start_time' => 'required|date_format:Y-m-d H:i',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['startTransfer'] = true;
        $result = $this->transferRepository->update($id,$data);
        if($result){
            $result = $this->transferRepository->getById($id);
            return $this->successResponse($result, __('response_messages.transfer.started'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/driver/transfers/stop-transfer/{id}",
     *      operationId="stopTransfer",
     *      tags={"DriverTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Driver Completed The Transfer",
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
     *                  required={"dropoff_time"},
     *                  @OA\Property(property="dropoff_time", type="datetime", format="dropoff_time", example="2023-09-22 20:52"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Successfully Completed"),
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
    public function stopTransfer(Request $request, $id)
    {
        $$data = $request->only('dropoff_time');
        $validator = Validator::make($data, [
            'dropoff_time' => 'required|date_format:Y-m-d H:i',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['stopTransfer'] = true;
        $result = $this->transferRepository->update($id,$data);
        if($result){
            $result = $this->transferRepository->getById($id);
            return $this->successResponse($result, __('response_messages.transfer.completed'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transfers/attachPassengers",
     *      operationId="attachPassengers",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="attachPassengers",
     *      description="Returns Transfer data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"passenger_id[]","transfer_id"},
     *                  @OA\Property(property="transfer_id", type="integer", format="transfer_id", example="1"),
     *                  @OA\Property(property="passenger_id[]", type="array", @OA\Items(type="integer",default="1"), uniqueItems=true)
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Passenger Successfully Attached"),
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
    public function attachPassengers(Request $request)
    {
        $data = $request->only('passenger_id','transfer_id');
        $validator = Validator::make($data, [
            'transfer_id' => 'required|exists:transfers,id',
            'passenger_id' => 'required|array|min:1',
            'passenger_id.*' => 'required|exists:passengers,id',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferRepository->attachPassengers($request);
        return $result;
        if($result){
            return $this->successResponse($result, __('response_messages.transfer.passengers'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @OA\Get(
     *      path="/api/companies/transfers/uetdsfile/{id}",
     *      operationId="uetdsTransferFile",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Uetds Transfer File by UETDS ID",
     *      description="Get Uetds Transfer File URL",
     *      @OA\Parameter(
     *          name="id",
     *          description="Uetds Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="UETDS File Successfully Created"),
     *              @OA\Property(property="data", type="string", example="url"),
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
    public function generateUetdsPdf($id)
    {
        $result = $this->transferRepository->uetdsPdf($id);
        if($result){
            return $this->successResponse('https://transferhub360.s3.amazonaws.com/'.$result,__('response_messages.transfer.pdf'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @OA\Get(
     *      path="/api/companies/transfers/typecount/{date}/{id}",
     *      operationId="countOfTransfeByDateAndDrive",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get Transfer count",
     *      description="Get Transfer count group by Type, Date and Driver Id",
     *      @OA\Parameter(
     *          name="date",
     *          description="Pickup Date",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Driver Id",
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
     *              @OA\Property(property="data", type="string", example="count"),
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
    public function groupByType($date, $id = null)
    {
        $result = $this->transferRepository->groupByType($date, $id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/transfers/passengers/{id}",
     *      operationId="getPassengersByTransferId",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Passengers By Transfer Id",
     *      description="Get Transfer Passenger Data by Transfer Id",
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
     *              @OA\Property(property="data", type="string", example="array of Transfer Passenger Data"),
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
    public function passenger($id)
    {
        $result = $this->transferRepository->passenger($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

}
