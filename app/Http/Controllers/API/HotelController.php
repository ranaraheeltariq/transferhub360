<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\HotelRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    use ApiResponser;

    private HotelRepository $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/hotels",
     *      operationId="geHotelsList",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Hotels",
     *      description="Returns list of Hotels",
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
     *              @OA\Property(property="data", type="string", example="array of Hotels list"),
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
        $result = $this->hotelRepository->getAll($request);
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/hotels/create",
     *      operationId="storeHotel",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Hotel",
     *      description="Returns Hotel data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name","country_code","country","city_code","city","zone_code","zone","location"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example=""),
     *                  @OA\Property(property="name", type="string", format="name", example="Nova Clinic Hotel"),
     *                  @OA\Property(property="country_code", type="string", format="country_code", example="TR"),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkiye"),
     *                  @OA\Property(property="city_code", type="integer", format="city_code", example="34"),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="zone_code", type="integer", format="zone_code", example="1852"),
     *                  @OA\Property(property="zone", type="string", format="zone", example="ÜMRANİYE"),
     *                  @OA\Property(property="location", type="string", format="location", example="Ilhamurkoye"),
     *                  @OA\Property(property="phone", type="number", format="phone", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
     *                  @OA\Property(property="website", type="string", format="website", example="http://www.alfanovatech.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Hotel Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Hotel data"),
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
        $data = $request->only('customer_id','name','country_code','country','city_code','city','zone_code','zone','location','phone','email','website','thumbnail','status');
        $validator = Validator::make($data,[
            'customer_id' => 'nullable|string|max:255|exists:customers,id',
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city_code' => 'required|integer',
            'city' => 'required|string|max:255',
            'zone_code' => 'required|integer',
            'zone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'status' => 'nullable|in:Active,Deactive',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->hotelRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.hotel.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/hotels/detail/{id}",
     *      operationId="getHotelById",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Hotel By Id",
     *      description="Get Hotel Data by Hotel Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Hotel Id",
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
     *              @OA\Property(property="data", type="string", example="array of Hotel Data"),
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
        $result = $this->hotelRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/hotels/update/{id}",
     *      operationId="updateHotel",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Hotel",
     *      description="Returns Hotel data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Hotel Id",
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
     *                  required={"name","country_code","country","city_code","city","zone_code","zone","location"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example=""),
     *                  @OA\Property(property="name", type="string", format="name", example="Nova Clinic Hotel"),
     *                  @OA\Property(property="country_code", type="string", format="country_code", example="TR"),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkiye"),
     *                  @OA\Property(property="city_code", type="integer", format="city_code", example="34"),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="zone_code", type="integer", format="zone_code", example="1852"),
     *                  @OA\Property(property="zone", type="string", format="zone", example="ÜMRANİYE"),
     *                  @OA\Property(property="location", type="string", format="location", example="Ilhamurkoye"),
     *                  @OA\Property(property="phone", type="number", format="phone", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
     *                  @OA\Property(property="website", type="string", format="website", example="http://www.alfanovatech.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Hotel Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Hotel Data"),
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
        $data = $request->only('customer_id','name','country_code','country','city_code','city','zone_code','zone','location','phone','email','website','thumbnail','status');
        $validator = Validator::make($data,[
            'customer_id' => 'nullable|string|max:255|exists:customers,id',
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city_code' => 'required|integer',
            'city' => 'required|string|max:255',
            'zone_code' => 'required|integer',
            'zone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'status' => 'nullable|in:Active,Deactive',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->hotelRepository->update($id,$data);
        if($result){
            $result = $this->hotelRepository->getById($id);
            return $this->successResponse($result, __('response_messages.customer.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/hotels/delete/{id}",
     *      operationId="destoryHotel",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Hotel",
     *      description="Remove Hotel by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Hotel Id",
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
     *              @OA\Property(property="message", type="string", example="Hotel Deleted Successfully"),
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
        $result = $this->hotelRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.hotel.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
