<?php

namespace App\Http\Services\Users;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Http\Services\Users\SalesmanService;
use App\Model\Accurate\USERS as Model;

class UserService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new Model();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('USERID', $id)->first();
  }

  public static function insert($data) {
    $USERID = Increment::GETUSERID();

    $FULLNAME = "";
    if (isset($data['firstName'])) {
      $FULLNAME = $data['firstName'];
    }
    if (isset($data['lastName'])) {
      $FULLNAME = isset($data['firstName']) ? $data['firstName'].' '.$data['lastName'] : $data['lastName'];
    }

    $insert = new Model();

    $insert->USERID = $USERID;
    $insert->USERNAME = isset($data['username']) ? $data['username'] : null;
    $insert->USERLEVEL = isset($data['userLevel']) ? $data['userLevel'] : 0;
    $insert->FULLNAME = $FULLNAME;
    $insert->USERPASSWORD = "-1";
    $insert->SALESC = 1;
    $insert->SALESR = 1;
    $insert->SOC = 1;
    $insert->SOE = 1;
    $insert->SOR = 1;
    $insert->DOC = 1;
    $insert->DOR = 1;
    $insert->CMC = 1;
    $insert->CMR = 1;
    $insert->PRINTSALESINVOICE = 1;
    $insert->CHANGECINFO = 1;
    $insert->CHANGESELLINGPRICE = 1;
    $insert->CUSTOMERR = 1;
    $insert->VENDORC = 1;
    $insert->REPRINTINVOICE = 1;
    $insert->SALESV = 1;
    $insert->SALESL = 1;
    $insert->SOV = 1;
    $insert->SOL = 1;
    $insert->CMV = 1;
    $insert->DOV = 1;
    $insert->DOL = 1;
    $insert->save();
    if ($insert) {
      SalesmanService::insert([
        'salesmanId' => $USERID,
        'firstName' => isset($data['username']) ? $data['username'] : null,
        'lastName' => isset($data['lastName']) ? $data['lastName'] : null
      ]);
      return $insert;
    }
    return false;
  }

  public static function update($id, $data) {
    $FULLNAME = "";
    if (isset($data['firstName'])) {
      $FULLNAME = $data['firstName'];
    }
    if (isset($data['lastName'])) {
      $FULLNAME = isset($data['firstName']) ? $data['firstName'].' '.$data['lastName'] : $data['lastName'];
    }
    
    $update = Model::where('USERID', $id)->update([
      "USERNAME" => isset($data['username']) ? $data['username'] : null,
      "USERLEVEL" => isset($data['userLevel']) ? $data['userLevel'] : 0,
      "FULLNAME" => $FULLNAME,
    ]);
    
    if ($update) {
      return SalesmanService::update($id, [
        'firstName' => isset($data['firstName']) ? $data['firstName'] : null,
        'lastName' => isset($data['lastName']) ? $data['lastName'] : null
      ]);
      return $update;
    }
    return false;
  }

  public static function delete($id) {
    $delete = Model::where('USERID', $id)->delete();
    if ($delete) return $delete;
    return false;
  }

  public static function count($query, $FIELDS = null) {
    $model = new Model();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}