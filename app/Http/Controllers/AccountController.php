<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Account\AccountService as Service;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class AccountController extends BaseController
{
  protected $fillable = [
    'id',
    'type',
    'description',
    'value',
    'created_at',
    'updated_at'
  ];

  /**
  * @SWG\Get(
  *   path="/setting/account/{id}",
  *   summary="Find Account Payment by Type or Id",
  *   tags={"Setting Account Payment"},
  * 	operationId="findOne",
  *   @SWG\Parameter(
  *     name="id",
  *     description="type or id of item that needs to be fetched",
  *     in="path",
  *     required=true,
  *     type="number"
  *   ),
  *   security = { { "Bearer": {} } },
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", ref="$/definitions/AccountPayment")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function findOne($id) {
    try {
      $data = Service::findById($id, $this->fillable);
      if ($data) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
        ], $data);
      }
      return ResponseService::ApiError(404, "User not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Get(
  *   path="/setting/account",
  *   summary="Find All Type Payment with Paginate",
  *   tags={"Setting Account Payment"},
  * 	operationId="findAll",
  *   security = { { "Bearer": {} } },
  *   @SWG\Parameter(name="page", description="Page - eg: 1", in="query", type="number"),
  *   @SWG\Parameter(name="limit", description="Limit Data - eg: 10", in="query", type="number"),
  *   @SWG\Parameter(name="keyword", description="Search - eg: Name", in="query", type="string"),
  *   @SWG\Parameter(name="sort", description="Sorting Field - eg: - ID (desc by id)", in="query", type="string"),
  *   @SWG\Parameter(name="type", description="Retrieve data without paginate query - eg: all", in="query", type="string"),
  *   @SWG\Parameter(name="from", description="Retrieve data by date from - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="to", description="Retrieve data by date to - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="field", description="Retrieve data based on the input fields - eg: ID, NAME", in="query", type="string"),
  *   @SWG\Parameter(name="relationship", description="View relationship of data - eg: 0", in="query", type="string"),
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponsePaginate",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", @SWG\Items(ref="#/definitions/AccountPayment"))
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function findAll(Request $request) {
    try {
      $data = Service::getAll($request->all(), $this->fillable);
      if ($data) {
        $allowed = [
          "keyword",
          "totalData",
          "perPage",
          "lastPage",
          "currentPage"
        ];
        
        $paginate = Query::filterAllowedField($allowed, $data);
        $dataRes = Query::filterDisallowField($data, $paginate);
    
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
          "paginate" => $paginate
        ], $dataRes['data']);
      }
      return ResponseService::ApiError(404, [
        "message"=>"Error"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function create(Request $request) {
    try {
      $data = $request->all();
      $checkType = Service::findByType($data['type']);

      if (!$checkType) {       
        $create = Service::insert($data);
        if ($create) {
            return ResponseService::ApiSuccess(201, [
            "message"=>"Successfully Created Account"
            ], $create);
        }
        return ResponseService::ApiError(422, [
            "message"=>"Error Creating Account"
        ], "Error"); 
      }
      return ResponseService::ApiError(422, [
          "message"=>"Type Account Already Exist!"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Put(
  *   path="/setting/account/{id}",
  *   summary="Update Account Payment",
  *   tags={"Setting Account Payment"},
  * 	operationId="edit",
  *   @SWG\Parameter(name="id",description="Id or Type of account payment",in="path",required=true,type="number"),
  *   @SWG\Parameter(
  *     name="Request",
  *     description="Input request",
  *     in="body",
  *     required=true,
  *     @SWG\Schema(
  *       type="object",
  *       @SWG\Property(property="value",type="string", example="110302.1"),
  *     )
  *   ),
  *   security = { { "Bearer": {} } },
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", ref="$/definitions/User")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function edit(Request $request, $id) {
    try {
      $find = Service::findById($id);
      if ($find) { 
        $update = Service::update($id, $request->all());
        if ($update) {
          return ResponseService::ApiSuccess(200, [
            "message"=>"Successfully Updated GL-Account"
          ], $update);
        }
        return ResponseService::ApiError(422, "Error Updating GL-Account");
      }
      return ResponseService::ApiError(404, "GL-Account not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function destroy($id) {
    try {
      $data = Service::findById($id);
      if ($data) {
        $delete = Service::delete($id);
        if ($delete) {
          return ResponseService::ApiSuccess(200, [
            "message"=>"Successfully Deleted Salesman",
          ], $delete);
        }
        return ResponseService::ApiError(404, "Error Updating Salesman");
      }
      return ResponseService::ApiError(404, "Salesman not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function countData(Request $request) {
    try {
      $data = Service::count($request->all(), $this->fillable);
      return ResponseService::ApiSuccess(200, [
        "message"=>"Success"
      ], ["count"=>$data]);
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
}
