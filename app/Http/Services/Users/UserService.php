<?php

namespace App\Http\Services\Users;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Model\Accurate\USERS as Model;

class UserService extends BaseController
{
  public static function getAll($query) {
    $FIELDS = ['ID','PERSONNO','NAME', 'PERSONTYPE', 'PHONE', 'EMAIL', 'ADDRESSLINE1', 'PRICELEVEL', 'CITY', 'TRANSACTIONID', 'CUSTOMERTYPEID', 'CREDITLIMITDAYS', 'CREDITLIMIT'];
    $model = new PERSONDATA();
    $query['PERSONTYPE'] = 0;
    $query['SUSPENDED'] = 0;
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return Model::select($FIELDS ?: '*')
    ->where('USERID', $id)->first();
  }
}