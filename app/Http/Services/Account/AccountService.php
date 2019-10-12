<?php

namespace App\Http\Services\Account;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Model\Mysql\Account as Model;

class AccountService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new Model();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('id', $id)->first();
  }

  public static function findByType($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('type', $id)->first();
  }

  public static function insert($data) {
    $insert = new Model();
    $insert->type = $data['type'];
    $insert->description = isset($data['description']) ? $data['description'] : null;
    $insert->value = $data['value'];
    $insert->save();

    if ($insert) return $insert;
    return false;
  }

  public static function update($id, $data) {
    $update = Model::find($id);
    $update->description = isset($data['description']) ? $data['description'] : null;
    $update->value = $data['value'];
    $update->save();

    if ($update) return $update;
    return false;
  }

  public static function delete($id) {
    $delete = Model::find($id)->delete();
    if ($delete) return $delete;
    return false;
  }

  public static function count($query, $FIELDS = null) {
    $model = new Model();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}