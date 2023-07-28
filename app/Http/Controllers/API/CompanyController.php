<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use ApiResponser;

    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/admin/companies?page={number}",
     *      operationId="getCompanyList",
     *      tags={"AdminCompany"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Companies",
     *      description="Returns list of Companies",
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
     *              @OA\Property(property="data", type="string", example="array of companies list"),
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
        $companies = $this->companyRepository->getAll();
        return $this->successResponse($companies);
    }

    /**
     * @OA\Post(
     *      path="/api/admin/companies/create",
     *      operationId="storeCompany",
     *      tags={"AdminCompany"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Company",
     *      description="Returns company data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name","contact_number","address","city","country"},
     *                  @OA\Property(property="name", type="string", format="name", example="Tour Campaign"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkey"),
     *                  @OA\Property(property="note", type="string", format="note", example=""),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel Tariq"),
     *                  @OA\Property(property="contact_person_no", type="integer", format="contact_person_no", example="00905340344609"),
     *                  @OA\Property(property="company_legal_name", type="string", format="company_legal_name", example="Alfa Nova Transportation"),
     *                  @OA\Property(property="uetds_url", type="string", format="uetds_url", example="http://uetds.com"),
     *                  @OA\Property(property="uetds_username", type="string", format="uetds_username", example="alfanovauets"),
     *                  @OA\Property(property="uetds_password", type="password", format="uetds_password", example="dfdf323"),
     *                  @OA\Property(property="uetds_status", type="string", format="uetds_status", example="active"),
     *                  @OA\Property(property="customer_create_limit", type="integer", format="customer_create_limit", example="0", description="0 for unlimited"),
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
     *              @OA\Property(property="message", type="string", example="Company Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of company list"),
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
        $data = $request->only('name','contact_number','email','address','thumbnail','city','country','note','contact_person','contact_person_no','company_legal_name','uetds_url','uetds_username','uetds_password','uetds_status','customer_create_limit','demo_end_at','subscription_start_at','subscription_end_at','type','status','payment_on','payment_status','payment_note','source_of_booking');
        $validator = Validator::make($data,[
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_no' => 'nullable|string|max:255',
            'company_legal_name' => 'nullable|string|max:255',
            'uetds_url' => 'nullable|string|max:255',
            'uetds_username' => 'nullable|string|max:255',
            'uetds_password' => 'nullable|string|max:255',
            'uetds_status'  =>  'nullable|string|max:255',
            'customer_create_limit' => 'nullable|integer',
            'demo_end_at'   => 'nullable|date|after:now',
            'subscription_start_at' => 'nullable|date|after:now',
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
        $result = $this->companyRepository->create($data);
        if($result) {
            return $this->successResponse($result, __('response_messages.Company.created'), Response::HTTP_CREATED);
        }
        return $this->errorResponse(__('response_messages._common.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *      path="/api/admin/companies/detail/{id}",
     *      operationId="getCompanyById",
     *      tags={"AdminCompany"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Company By Id",
     *      description="Get Company Data by Company Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Compnay Id",
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
     *              @OA\Property(property="data", type="string", example="array of company"),
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
        $company = $this->companyRepository->getById($id);
        if($company){
            return $this->successResponse($company);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/admin/companies/update/{id}",
     *      operationId="updateCompany",
     *      tags={"AdminCompany"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Company",
     *      description="Returns company data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Compnay Id",
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
     *                  required={"name","contact_number","address","city","country"},
     *                  @OA\Property(property="name", type="string", format="name", example="Tour Campaign"),
     *                  @OA\Property(property="contact_number", type="number", format="contact_number", example="00905340344609"),
     *                  @OA\Property(property="address", type="string", format="address", example="Zafar Mah. Cahmuriyat Cadde. Bachelivler"),
     *                  @OA\Property(property="thumbnail", type="file", format="thumbnail", example=""),
     *                  @OA\Property(property="city", type="string", format="city", example="Istanbul"),
     *                  @OA\Property(property="country", type="string", format="country", example="Turkey"),
     *                  @OA\Property(property="note", type="string", format="note", example=""),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel Tariq"),
     *                  @OA\Property(property="contact_person_no", type="integer", format="contact_person_no", example="00905340344609"),
     *                  @OA\Property(property="company_legal_name", type="string", format="company_legal_name", example="Alfa Nova Transportation"),
     *                  @OA\Property(property="uetds_url", type="string", format="uetds_url", example="http://uetds.com"),
     *                  @OA\Property(property="uetds_username", type="string", format="uetds_username", example="alfanovauets"),
     *                  @OA\Property(property="uetds_password", type="password", format="uetds_password", example="dfdf323"),
     *                  @OA\Property(property="uetds_status", type="string", format="uetds_status", example="active"),
     *                  @OA\Property(property="customer_create_limit", type="integer", format="customer_create_limit", example="0", description="0 for unlimited"),
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
     *              @OA\Property(property="message", type="string", example="Company Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of company list"),
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
        $data = $request->only('name','contact_number','email','address','thumbnail','city','country','note','contact_person','contact_person_no','company_legal_name','uetds_url','uetds_username','uetds_password','uetds_status','customer_create_limit','demo_end_at','subscription_start_at','subscription_end_at','type','status','payment_on','payment_status','payment_note','source_of_booking');
        $validator = Validator::make($data,[
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'thumbnail' => 'nullable|mimes:jpg,png,gif,jpeg,jpe|max:5120',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_no' => 'nullable|string|max:255',
            'company_legal_name' => 'nullable|string|max:255',
            'uetds_url' => 'nullable|string|max:255',
            'uetds_username' => 'nullable|string|max:255',
            'uetds_password' => 'nullable|string|max:255',
            'uetds_status'  =>  'nullable|string|max:255',
            'customer_create_limit' => 'nullable|integer',
            'demo_end_at'   => 'nullable|date|after:now',
            'subscription_start_at' => 'nullable|date|after:now',
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
        $company = $this->companyRepository->update($id,$data);
        if($company){
            return $this->successResponse($Owner, __('response_messages.Company.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/admin/companies/delete/{id}",
     *      operationId="destoryCompany",
     *      tags={"AdminCompany"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Company",
     *      description="Remove Company by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Compnay Id",
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
     *              @OA\Property(property="data", type="string", example="Company Deleted Successfully"),
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
        $company = $this->companyRepository->delete($id);
        if($company)
        {
            return $this->successResponse(null, __('response_messages.Company.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
