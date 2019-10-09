<?php

namespace App\Http\Services\Customers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Model\Accurate\PERSONDATA;
use App\Http\Services\Utils\Query;

class CustomerService extends BaseController
{
  public static function getAll($query) {
    $model = new PERSONDATA;
    $FIELDS = ['ID','PERSONNO','NAME'];
    $query['PERSONTYPE'] = 0;
    $query['SUSPENDED'] = 0;
    // $query['ID'] = 1;
    // return $query;
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }
}
