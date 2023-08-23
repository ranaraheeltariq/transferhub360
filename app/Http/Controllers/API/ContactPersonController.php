<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\ContactPersonRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ContactPersonController extends Controller
{
    use ApiResponser;

    private ContactPersonRepository $contactPersonRepository;

    public function __construct(ContactPersonRepository $contactPersonRepository)
    {
        $this->contactPersonRepository = $contactPersonRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/contact-persons",
     *      operationId="geContactPersonsList",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Contact Persons",
     *      description="Returns list of Contact Persons",
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
     *              @OA\Property(property="data", type="string", example="array of Contact Persons list"),
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
        $result = $this->contactPersonRepository->getAll($request);
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/contact-persons/create",
     *      operationId="storeContactPerson",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Contact Person",
     *      description="Returns Contact Person data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name","number"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example=""),
     *                  @OA\Property(property="name", type="string", format="name", example="Hamza Ali"),
     *                  @OA\Property(property="number", type="number", format="number", example="00905340344609"),
     *                  @OA\Property(property="whatsapp_number", type="number", format="whatsapp_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
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
     *              @OA\Property(property="message", type="string", example="Contact Person Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Contact Person data"),
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
        $data = $request->only('customer_id','name','number','whatsapp_number','email','thumbnail','status');
        $validator = Validator::make($data,[
            'customer_id' => 'nullable|string|max:255|exists:customers,id',
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'status' => 'nullable|in:Active,Deactive',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->contactPersonRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.contactPerson.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/contact-persons/detail/{id}",
     *      operationId="getContactPersonById",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Contact Person By Id",
     *      description="Get Contact Person Data by Contact Person Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Contact Person Id",
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
     *              @OA\Property(property="data", type="string", example="array of Contact Person Data"),
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
        $result = $this->contactPersonRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/contact-persons/update/{id}",
     *      operationId="updateContactPerson",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Contact Person",
     *      description="Returns Contact Person data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Contact Person Id",
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
     *                  required={"name","number"},
     *                  @OA\Property(property="customer_id", type="integer", format="customer_id", example=""),
     *                  @OA\Property(property="name", type="string", format="name", example="Hamza Ali"),
     *                  @OA\Property(property="number", type="number", format="number", example="00905340344609"),
     *                  @OA\Property(property="whatsapp_number", type="number", format="whatsapp_number", example="00905340344609"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
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
     *              @OA\Property(property="message", type="string", example="Contact Person Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Contact Person Data"),
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
        $data = $request->only('customer_id','name','number','whatsapp_number','email','thumbnail','status');
        $validator = Validator::make($data,[
            'customer_id' => 'nullable|string|max:255|exists:customers,id',
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'status' => 'nullable|in:Active,Deactive',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->contactPersonRepository->update($id,$data);
        if($result){
            $result = $this->contactPersonRepository->getById($id);
            return $this->successResponse($result, __('response_messages.contactPerson.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/contact-persons/delete/{id}",
     *      operationId="destoryContactPerson",
     *      tags={"CompanyDefination"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Contact Person",
     *      description="Remove Contact Person by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Contact Person Id",
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
     *              @OA\Property(property="message", type="string", example="Contact Person Deleted Successfully"),
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
        $result = $this->contactPersonRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.contactPerson.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
