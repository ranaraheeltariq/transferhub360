<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\TransferDetailRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransferDetailController extends Controller
{
    use ApiResponser;

    private TransferDetailRepository $transferDetailRepository;

    public function __construct(TransferDetailRepository $transferDetailRepository)
    {
        $this->transferDetailRepository = $transferDetailRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/companies/transferdetail/transfer/{id}",
     *      operationId="getTransfersDetailbyTransferId",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get list of Transfer Detail by Transfer Id",
     *      description="Returns list of Transfer Detail by Transfer Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer id",
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
     *              @OA\Property(property="data", type="string", example="array of Transfers detail"),
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
    public function index($id)
    {
        $result = $this->transferDetailRepository->getByTransferId($id);
        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transferdetail/create",
     *      operationId="storeTransferDetail",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Create New Transfer Detail",
     *      description="Returns Transfer Detail data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="I just fill Required Fields",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"transfer_id"},
     *                  @OA\Property(property="transfer_id", type="integer", format="transfer_id", example="1"),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel"),
     *                  @OA\Property(property="contact_number", type="string", format="contact_number", example="+905547778899"),
     *                  @OA\Property(property="flight_number", type="string", format="flight_number", example="PK434"),
     *                  @OA\Property(property="type", type="string", format="type", enum={"Arrival","Departure","Inner City"}, default="Inner City"),
     *                  @OA\Property(property="info", type="string", format="info", example="Deneme123"),
     *                  @OA\Property(property="file_path", type="file", format="file_path", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Detail Successfully Created"),
     *              @OA\Property(property="data", type="string", example="array of Transfer Detail data"),
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
        $data = $request->only('transfer_id','contact_person','contact_number','flight_number','type','info','file_path');
        $validator = Validator::make($data, [
            'transfer_id' => 'required|exists:transfers,id',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'flight_number' => 'nullable|string|max:255',
            'type'          => 'nullable|string|max:255',
            'info'          => 'nullable|string',
            'file_path' => 'nullable|mimes:jpg,jpeg,jpe,png,pdf,docx|max:5120',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferDetailRepository->create($data);
        if($result){
            return $this->successResponse($result, __('response_messages.transferDetail.created'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/api/companies/transferdetail/detail/{id}",
     *      operationId="getTransferDetailById",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Get of Transfer Detail By Id",
     *      description="Get Transfer Detail Data by Transfer Detail Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Detail Id",
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
        $result = $this->transferDetailRepository->getById($id);
        if($result){
            return $this->successResponse($result);
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transferdetail/update/{id}",
     *      operationId="updateTransferDetail",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Update Transfer Detail",
     *      description="Returns Transfer Detail data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Detail Id",
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
     *                  required={"transfer_id"},
     *                  @OA\Property(property="transfer_id", type="integer", format="transfer_id", example="1"),
     *                  @OA\Property(property="contact_person", type="string", format="contact_person", example="Raheel"),
     *                  @OA\Property(property="contact_number", type="string", format="contact_number", example="+905547778899"),
     *                  @OA\Property(property="flight_number", type="string", format="flight_number", example="PK434"),
     *                  @OA\Property(property="type", type="string", format="type", enum={"Arrival","Departure","Inner City"}, default="Inner City"),
     *                  @OA\Property(property="info", type="string", format="info", example="Deneme123"),
     *                  @OA\Property(property="file_path", type="file", format="file_path", example=""),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Transfer Detail Successfully Updated"),
     *              @OA\Property(property="data", type="string", example="array of Transfer Detail Data"),
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
        $data = $request->only('transfer_id','contact_person','contact_number','flight_number','type','info','file_path');
        $validator = Validator::make($data, [
            'transfer_id' => 'required|exists:transfers,id',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'flight_number' => 'nullable|string|max:255',
            'type'          => 'nullable|string|max:255',
            'info'          => 'nullable|string',
            'file_path' => 'nullable|mimes:jpg,jpeg,jpe,png,pdf,docx|max:5120',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $result = $this->transferDetailRepository->update($id,$data);
        if($result){
            $result = $this->transferDetailRepository->getById($id);
            return $this->successResponse($result, __('response_messages.transferDetail.updated'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/companies/transferdetail/delete/{id}",
     *      operationId="destoryTransferDetail",
     *      tags={"CompanyTransfer"},
     *      security={ {"bearerAuth":{} }},
     *      summary="Remove Transfer Detail",
     *      description="Remove Transfer Detail by Id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer Detail Id",
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
     *              @OA\Property(property="message", type="string", example="Transfer Detail Deleted Successfully"),
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
        $result = $this->transferDetailRepository->delete($id);
        if($result)
        {
            return $this->successResponse(null, __('response_messages.transferDetail.deleted'));
        }
        return $this->errorResponse(__('response_messages.common.404'),Response::HTTP_NOT_FOUND);
    }
}
