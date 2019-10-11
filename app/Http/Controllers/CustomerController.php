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
    try {
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
      return ResponseService::ApiError(404, [
        "message"=>"Error"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function findOne($id) {
    try {
      $data = Service::findById($id);
      if ($data) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
        ], $data);
      }
      return ResponseService::ApiError(404, "Customer not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function create(Request $request) {
    try {
      $create = Service::insert($request->all());
      if ($create) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Successfully Created Customer"
        ], $create);
      }
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  function edit(Request $request, $id) {
    try {
      $find = Service::findById($id);
      if ($find) { 
        $update = Service::update($id, $request->all());
        if ($update) {
          return ResponseService::ApiSuccess(200, [
            "message"=>"Successfully Updated Customer"
          ], $update);
        }
        return ResponseService::ApiError(422, "Error updating customer");
      }
      return ResponseService::ApiError(404, "Customer not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
}
