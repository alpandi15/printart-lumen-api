<?php

namespace App\Http\Services\Utils;
use App\Model\Mysql\Increment as Model;

use Laravel\Lumen\Routing\Controller as BaseController;

class Increment extends BaseController
{
  public static function insert ($data) {
    $insert = new Model();
    $insert->type = $data['type'];
    $insert->description = $data['description'];
    $insert->value = $data['value'];
    $insert->save();
    return $insert;
  }

  public static function findById ($id) {
    return Model::find($id);
  }

  public static function findOne ($query) {
    return Model::where($query)->get()->first();
  }

  public static function update ($data, $id) {
    return Model::where('id', $id)->update($data);
  }
}