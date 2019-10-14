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