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
    'type',
    'description',
    'value',
    'created_at',
    'updated_at'
  ];

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

  function edit(Request $request, $id) {
    try {
      $find = Service::findById($id);
      if ($find) { 
        $update = Service::update($id, $request->all());
        if ($update) {
          return ResponseService::ApiSuccess(200, [
            "message"=>"Successfully Updated Account"
          ], $update);
        }
        return ResponseService::ApiError(422, "Error Updating Account");
      }
      return ResponseService::ApiError(404, "Account not found");
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