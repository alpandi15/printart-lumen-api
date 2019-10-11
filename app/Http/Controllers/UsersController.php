<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Users\UserService;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ExternalAuth;
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
  
  function findOne($id) {
    try {
      $data = UserService::findById($id, $this->fillable);
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
}