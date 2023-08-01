<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use ApiResponser;

    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/customers?page={number}",
     *      operationId="getCustomersList",
     *      tags={"CompanyCustomer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Customers",
     *      description="Returns list of Customers",
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
     *              @OA\Property(property="data", type="string", example="array of Customers list"),
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
        $result = $this->customerRepository->getAll();
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/customers/create",
     *      operationId="storeCustomer",
     *      tags={"CompanyCustomer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Customer",
     *      description="Returns Customer data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"brand_name","contact_number","contact_person","country","city","zone","address"},
     *                  @OA\Property(property="legal_name", type="string", format="legal_name", example="Nova Clinic LTD"),
     *                  @OA\Property(property="brand_name", type="string", format="brand_name", example="Tour & Travel"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel Tariq"),
     *                  @OA\Property(property="whatsapp_number", type="number", format="whatsapp_number", example="00905340344609"),
     *                  @OA\Property(property="website", type="string", format="website", example="http://www.alfanovatech.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkey"),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="zone", type="string", format="zone", example="Bachelivler"),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="description", type="string", format="description", example=""),
     *                  @OA\Property(property="transfer_create_limit", type="integer", format="customer_create_limit", example="0", description="0 for unlimited"),
     *                  @OA\Property(property="demo_end_at", type="date", format="demo_end_at", example="2023-08-01"),
     *                  @OA\Property(property="subscription_start_at", type="date", format="subscription_start_at", example="2023-08-01"),
     *                  @OA\Property(property="subscription_end_at", type="date", format="subscription_end_at", example="2024-08-01"),
     *                  @OA\Property(property="type", type="in:Demo,Monthly,Yearly", format="type", example="Yearly"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *                  @OA\Property(property="payment_on", type="date", format="payment_on", example="2024-08-01"),
     *                  @OA\Property(property="payment_status", type="in:Pending,Processing,Completed", format="payment_status", example="Completed"),
     *                  @OA\Property(property="payment_note", type="text", format="payment_note", example="Payment received"),
     *                  @OA\Property(property="source_of_booking", type="string", format="source_of_booking", example="Facebook"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Customer Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Customer data"),
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
        $data = $request->only('legal_name','brand_name','email','contact_number','contact_person','whatsapp_number','website','thumbnail','country','city','zone','address','description','transfer_create_limit','demo_end_at','subscription_start_at','subscription_end_at','type','status','payment_on','payment_status','payment_note','source_of_booking');
        $validator = Validator::make($data,[
            'legal_name' => 'nullable|string|max:255',
            'brand_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:15',
            'contact_person' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'transfer_create_limit' => 'nullable|integer',
            'demo_end_at'   => 'nullable|date|after:yesterday',
            'subscription_start_at' => 'nullable|date|after:yesterday',
            'subscription_end_at'   => 'nullable|date|after:subscription_start_at',
            'type'  =>  'nullable|in:Demo,Monthly,Yearly',
            'status' => 'nullable|in:Active,Deactive',
            'payment_on' => 'nullable|date',
            'payment_status' => 'nullable|in:Pending,Processing,Completed',
            'payment_note' => 'nullable|string',
            'source_of_booking' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->customerRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.customer.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages.common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/customers/detail/{id}",
     *      operationId="getCustomerById",
     *      tags={"CompanyCustomer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Customer By Id",
     *      description="Get Customer Data by Customer Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer Id",
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
     *              @OA\Property(property="data", type="string", example="array of Customer Data"),
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
        $result = $this->customerRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/customers/update/{id}",
     *      operationId="updateCustomer",
     *      tags={"CompanyCustomer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Customer",
     *      description="Returns Customer data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer Id",
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
     *                  required={"brand_name","contact_number","contact_person","country","city","zone","address"},
     *                  @OA\Property(property="legal_name", type="string", format="legal_name", example="Nova Clinic LTD"),
     *                  @OA\Property(property="brand_name", type="string", format="brand_name", example="Tour & Travel"),
     *                  @OA\Property(property="email", type="email", format="email", example="tourtravel@yopmail.com"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel Tariq"),
     *                  @OA\Property(property="whatsapp_number", type="number", format="whatsapp_number", example="00905340344609"),
     *                  @OA\Property(property="website", type="string", format="website", example="http://www.alfanovatech.com"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkey"),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="zone", type="string", format="zone", example="Bachelivler"),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="description", type="string", format="description", example=""),
     *                  @OA\Property(property="transfer_create_limit", type="integer", format="customer_create_limit", example="0", description="0 for unlimited"),
     *                  @OA\Property(property="demo_end_at", type="date", format="demo_end_at", example="2023-08-01"),
     *                  @OA\Property(property="subscription_start_at", type="date", format="subscription_start_at", example="2023-08-01"),
     *                  @OA\Property(property="subscription_end_at", type="date", format="subscription_end_at", example="2024-08-01"),
     *                  @OA\Property(property="type", type="in:Demo,Monthly,Yearly", format="type", example="Yearly"),
     *                  @OA\Property(property="status", type="in:Active,Deactive", format="status", example="Active"),
     *                  @OA\Property(property="payment_on", type="date", format="payment_on", example="2024-08-01"),
     *                  @OA\Property(property="payment_status", type="in:Pending,Processing,Completed", format="payment_status", example="Completed"),
     *                  @OA\Property(property="payment_note", type="text", format="payment_note", example="Payment received"),
     *                  @OA\Property(property="source_of_booking", type="string", format="source_of_booking", example="Facebook"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Customer Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Customer Data"),
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
        $data = $request->only('legal_name','brand_name','email','contact_number','contact_person','whatsapp_number','website','thumbnail','country','city','zone','address','description','transfer_create_limit','demo_end_at','subscription_start_at','subscription_end_at','type','status','payment_on','payment_status','payment_note','source_of_booking');
        $validator = Validator::make($data,[
            'legal_name' => 'nullable|string|max:255',
            'brand_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:15',
            'contact_person' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'transfer_create_limit' => 'nullable|integer',
            'demo_end_at'   => 'nullable|date|after:yesterday',
            'subscription_start_at' => 'nullable|date|after:yesterday',
            'subscription_end_at'   => 'nullable|date|after:subscription_start_at',
            'type'  =>  'nullable|in:Demo,Monthly,Yearly',
            'status' => 'nullable|in:Active,Deactive',
            'payment_on' => 'nullable|date',
            'payment_status' => 'nullable|in:Pending,Processing,Completed',
            'payment_note' => 'nullable|string',
            'source_of_booking' => 'nullable|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->customerRepository->update($id,$data);
        if($result){
            $result = $this->customerRepository->getById($id);
            return $this->successResponse($result, __('response_messages.customer.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/customers/delete/{id}",
     *      operationId="destoryCustomer",
     *      tags={"CompanyCustomer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Customer",
     *      description="Remove Customer by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer Id",
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
     *              @OA\Property(property="message", type="string", example="Customer Deleted Successfully"),
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
        $result = $this->customerRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.customer.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
