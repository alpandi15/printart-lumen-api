<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Warehouse\WarehouseService as Service;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class WarehouseController extends BaseController
{
  private $fillable = [
    'WAREHOUSEID',
    'NAME',
    'DESCRIPTION',
    'ADDRESS1',
    'ADDRESS2',
    'ADDRESS3',
    'SUSPENDED'
  ];
  
  /**
  * @SWG\Get(
  *   path="/warehouse/{id}",
  *   summary="Find Warehouse by ID",
  *   tags={"Warehouse"},
  * 	operationId="findOne",
  *   @SWG\Parameter(
  *     name="id",
  *     description="WAREHOUSEID of Warehouse that needs to be fetched",
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
  *       @SWG\Property(property="data", ref="$/definitions/Wirehouse")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function findOne($id) {
    try {
      $suspended = Service::checkSuspended($id);
      if (!$suspended){
        $data = Service::findById($id, $this->fillable);
        if ($data) {
            return ResponseService::ApiSuccess(200, [
            "message"=>"Success",
            ], $data);
        }
        return ResponseService::ApiError(404, "Warehouse not found");
      }
      return ResponseService::ApiError(404, "Warehouse Suspended!");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Get(
  *   path="/warehouse",
  *   summary="Find All Warehouse with Paginate",
  *   tags={"Warehouse"},
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
  *       @SWG\Property(property="data", @SWG\Items(ref="#/definitions/Wirehouse"))
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
  * @SWG\Get(
  *   path="/warehouse-count",
  *   summary="Count Warehouse Data",
  *   tags={"Warehouse"},
  * 	operationId="countData",
  *   security = { { "Bearer": {} } },
  *   @SWG\Parameter(name="keyword", description="Search - eg: Name", in="query", type="string"),
  *   @SWG\Parameter(name="from", description="Retrieve data by date from - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="to", description="Retrieve data by date to - eg: 2019-08-01", in="query", type="string"),
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