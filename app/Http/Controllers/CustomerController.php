<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Customers\CustomerService as Service;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class CustomerController extends BaseController
{
  function findAll(Request $request) {
    $data = Service::getAll($request->all());
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
  }

  function create(Request $request) {
    $create = Service::insert($request->all());
    if ($create) {
      return response()->json($create, 200);
    }
    return response()->json(["message" => "Error"], 422);
  }
}
