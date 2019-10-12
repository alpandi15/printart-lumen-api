<?php

namespace App\Http\Services\Users;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Http\Services\History\TransHistoryService;
use App\Model\Accurate\SALESMAN as Model;

class SalesmanService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new Model();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('SALESMANID', $id)->first();
  }

  public static function insert($data) {
    // CREATE TRANSACTION
    $TRANSHISTORY = TransHistoryService::create([
      "TRANSTYPE" => 30, // ID SALESMAN
      "BRANCHCODEID" => 1,
      "STATUS" => 1,
      "USERID" => 0
    ]);

    if ($TRANSHISTORY) {
      $SALESMANID = Increment::GETSALESMANID();
      $TRANSACTIONID = $TRANSHISTORY[0]->TRANSACTIONID;

      $insert = new Model();
      $insert->SALESMANID = $SALESMANID;
      $insert->LASTNAME = $data['lastName'];
      $insert->FIRSTNAME = $data['firstName'];
      $insert->SALESMANNAME = $data['firstName'].' '.$data['lastName'];
      $insert->JOBTITLE = 'Reception';
      $insert->BRANCHCODEID = 1;
      $insert->TRANSACTIONID = $TRANSACTIONID;
      $insert->save();
  
      if ($insert) return $insert;
      return false;
    }
    return false;
  }

  public static function update($id, $data) {
    $update = Model::where('USERID', $id)->update([
      "USERNAME" => isset($data['username']) ? $data['username'] : null,
      "USERLEVEL" => isset($data['userLevel']) ? $data['userLevel'] : 0,
      "FULLNAME" => isset($data['fullName']) ? $data['fullName'] : null,
    ]);

    if ($update) return $update;
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