<?php

namespace App\Http\Services\Users;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Model\Accurate\USERS as Model;

class UsersServices extends BaseController
{
  public static function findOne($query) {
    return Model::where($query)->get()->first();
  }
}