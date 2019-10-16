<?php

namespace App\Http\Services\Account;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Model\Mysql\Account as Model;
use DB;

class AccountService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new Model();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('id', $id)
    ->orWhere('type', $id)
    ->first();
  }

  public static function findByType($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('type', $id)->first();
  }

  public static function insert($data) {
    try {
      $insert = new Model();
      $insert->type = $data['type'];
      $insert->description = isset($data['description']) ? $data['description'] : null;
      $insert->value = $data['value'];
      $insert->save();

      if ($insert) return $insert;
      return false;
    } catch (Exception $e) {
      DB::rollback();
      return false;
    }
  }

  public static function update($id, $data) {
    try {
      DB::beginTransaction();
      $update = Model::where('id', $id)->orWhere('type', $id)->first();
      $update->value = $data['value'];
      $update->save();
      DB::commit();
      if ($update) return $update;
      return false;
    } catch (Exception $e) {
      DB::rollback();
      return false;
    }
  }

  public static function delete($id) {
    $delete = Model::find($id)->delete();
    if ($delete) return $delete;
    return false;
  }

  public static function count($query = [], $FIELDS = null) {
    $model = new Model();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}
