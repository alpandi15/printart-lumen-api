<?php

namespace App\Http\Services\Warehouse;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Model\Accurate\WAREHS as Model;

class WarehouseService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new Model();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('WAREHOUSEID', $id)->first();
  }

  public static function checkSuspended($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('WAREHOUSEID', $id)
    ->where('SUSPENDED', '!=', 0)
    ->first();
  }

  public static function count($query, $FIELDS = null) {
    $model = new Model();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}