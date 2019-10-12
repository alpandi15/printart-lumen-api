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
      $FULLNAME = "";
      if (isset($data['firstName'])) {
        $FULLNAME = $data['firstName'];
      }
      if (isset($data['lastName'])) {
        $FULLNAME = isset($data['firstName']) ? $data['firstName'].' '.$data['lastName'] : $data['lastName'];
      }

      $insert = new Model();
      $insert->SALESMANID = $SALESMANID;
      $insert->LASTNAME = isset($data['lastName']) ? $data['lastName'] : null;
      $insert->FIRSTNAME = isset($data['firstName']) ? $data['firstName'] : null;
      $insert->SALESMANNAME = $FULLNAME;
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
    $FULLNAME = "";
    if (isset($data['firstName'])) {
      $FULLNAME = $data['firstName'];
    }
    if (isset($data['lastName'])) {
      $FULLNAME = isset($data['firstName']) ? $data['firstName'].' '.$data['lastName'] : $data['lastName'];
    }

    $update = Model::where('SALESMANID', $id)->update([
      "LASTNAME" => isset($data['lastName']) ? $data['lastName'] : null,
      "FIRSTNAME" => isset($data['firstName']) ? $data['firstName'] : null,
      "SALESMANNAME" => $FULLNAME,
    ]);

    if ($update) return $update;
    return false;
  }

  public static function delete($id) {
    $delete = Model::where('SALESMANID', $id)->delete();
    if ($delete) return $delete;
    return false;
  }

  public static function count($query, $FIELDS = null) {
    $model = new Model();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}