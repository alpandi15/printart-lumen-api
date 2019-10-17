<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Users\UserService as Service;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class UsersController extends BaseController
{
  private $fillable = [
    'USERID',
    'USERNAME',
    'USERLEVEL',
    'FULLNAME',
    'USERPASSWORD',
    'SALESC',
    'SALESR',
    'SOC',
    'SOE',
    'SOR',
    'DOC',
    'DOR',
    'CMC',
    'CMR',
    'PRINTSALESINVOICE',
    'CHANGECINFO',
    'CHANGESELLINGPRICE',
    'CUSTOMERR',
    'VENDORC',
    'REPRINTINVOICE',
    'SALESV',
    'SALESL',
    'SOV',
    'SOL',
    'CMV',
    'DOV',
    'DOL'
  ];

  /**
  * @SWG\Get(
  *   path="/users/{id}",
  *   summary="Find User by Id",
  *   tags={"Users"},
  * 	operationId="findOne",
  *   @SWG\Parameter(
  *     name="id",
  *     description="ID of user that needs to be fetched",
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
  *       @SWG\Property(property="data", ref="$/definitions/User")
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
  *   path="/users",
  *   summary="Find All Users with Paginate",
  *   tags={"Users"},
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
  *       @SWG\Property(property="data", @SWG\Items(ref="#/definitions/User"))
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

  /**
  * @SWG\Post(
  *   path="/users",
  *   summary="Create User",
  *   tags={"Users"},
  * 	operationId="create",
  *   @SWG\Parameter(
  *     name="Request",
  *     description="Input request",
  *     in="body",
  *     required=true,
  *     @SWG\Schema(ref="#/definitions/RequestUser")
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
  function create(Request $request) {
    try {
      $create = Service::insert($request->all());
      if ($create) {
        return ResponseService::ApiSuccess(201, [
          "message"=>"Successfully Created User"
        ], $create);
      }
      return ResponseService::ApiError(422, [
        "message"=>"Error Creating User"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Put(
  *   path="/users/{id}",
  *   summary="Update User",
  *   tags={"Users"},
  * 	operationId="edit",
  *   @SWG\Parameter(name="id",description="ID of user",in="path",required=true,type="number"),
  *   @SWG\Parameter(
  *     name="Request",
  *     description="Input request",
  *     in="body",
  *     required=true,
  *     @SWG\Schema(ref="#/definitions/RequestUser")
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
            "message"=>"Successfully Updated User"
          ], $update);
        }
        return ResponseService::ApiError(422, "Error Updating User");
      }
      return ResponseService::ApiError(404, "User not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Delete(
  *   path="/users/{id}",
  *   summary="Delete User",
  *   tags={"Users"},
  * 	operationId="destroy",
  *   @SWG\Parameter(
  *     name="id",
  *     description="ID of user",
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
  *       @SWG\Property(property="data", type="string")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function destroy($id) {
    try {
      $data = Service::findById($id);
      if ($data) {
        $delete = Service::delete($id);
        if ($delete) {
          return ResponseService::ApiSuccess(200, [
            "message"=>"Successfully Deleted User",
          ], $delete);
        }
        return ResponseService::ApiError(404, "Error Updating User");
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
  *   path="/users-count",
  *   summary="Count User Data",
  *   tags={"Users"},
  * 	operationId="countData",
  *   security = { { "Bearer": {} } },
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", type="object",
  *         @SWG\Property(property="count", type="number")
  *       )
  *     )
  *   ),
  *   @SWG\Response(response=422, description="Error Response", ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
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