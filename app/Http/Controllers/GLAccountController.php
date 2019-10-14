<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\GLAccount\GLAccountService as Service;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class GLAccountController extends BaseController
{
  private $fillable = [
    'GLACCOUNT',
    'CURRENCYID',
    'ACCOUNTNAME',
    'ACCOUNTTYPE',
    'SUBACCOUNT',
    'PARENTACCOUNT',
    'SUSPENDED',
    'MEMO',
    'FIRSTPARENTACCOUNT',
    'INDENTLEVEL',
    'ISFISCAL',
    'ISALLOCTOPROD',
    'TRANSACTIONID',
    'IMPORTEDTRANSACTIONID',
    'BRANCHCODEID',
    'LFT',
    'RGT',
    'ISROOT',
    'NEXTINVOICENO'
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
        return ResponseService::ApiError(404, "Account not found");
      }
      return ResponseService::ApiError(404, "Account Suspended!");
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