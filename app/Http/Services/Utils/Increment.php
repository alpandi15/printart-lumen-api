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

  public static function createNewNumber($type) {
    $findNumber = Increment::findOne(['type' => $type]);
    $lastNumber = 0;
    if ($findNumber) {
      $lastNumber = $findNumber['value'] + 1;
      Increment::update([
        "description" => \env('TYPE_NUMBER').$lastNumber,
        "value" => $lastNumber
      ], $findNumber['id']);
    } else {
      $lastNumber = Increment::insert([
        'type' => 'PERSONNO',
        "description" => \env('TYPE_NUMBER').'900000001',
        "value" => 900000001
      ])['value'];
    }
    return $lastNumber;
  }
}