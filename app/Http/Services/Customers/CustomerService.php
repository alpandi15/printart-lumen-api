<?php

namespace App\Http\Services\Customers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Model\Accurate\PERSONDATA;
use App\Model\Accurate\TRANSHISTORY;

class CustomerService extends BaseController
{
  public static function getAll($query) {
    $model = new PERSONDATA;
    $FIELDS = ['ID','PERSONNO','NAME'];
    $query['PERSONTYPE'] = 0;
    $query['SUSPENDED'] = 0;
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function insert($request) {
    $data = $request;
    // $lastId = PERSONDATA::max('ID');
    // $lastTransHistId = TRANSHISTORY::max('TRANSACTIONID');
    // $lastPersonno = PERSONDATA::SELECT("CAST");
    // $data = [
    //   'type' => 'TRNASNO',
    //   'description' => 'TRANSACTION NUMBER',
    //   'value' => 900000001
    // ];
    // $PERSONNO = $data['personNo'];
    $LAST_PERSONNO = 0;
    $LAST_PERSONID = 0;
    $LAST_TRANSACTIONID = 0;

    $PERSONNO = Increment::findOne(["type" => "PERSONNO" ]);
    $PERSONID = Increment::findOne(["type" => "PERSONID" ]);
    $TRANSACTIONID = Increment::findOne(["type" => "TRANSACTIONID" ]);

    if ($PERSONNO) {
      $LAST_PERSONNO = $PERSONNO['value'] + 1;
      Increment::update([
        "description" => 'E'.$LAST_PERSONNO,
        "value" => $LAST_PERSONNO
      ], $PERSONNO['id']);
    } else {
      $LAST_PERSONNO = Increment::insert([
        'type' => 'PERSONNO',
        "description" => 'E900000001',
        "value" => 900000001
      ])['value'];
    }

    if ($PERSONID) {
      $LAST_PERSONID = $PERSONID['value'] + 1;
      Increment::update([
        "description" => 'E'.$LAST_PERSONID,
        "value" => $LAST_PERSONID
      ], $PERSONID['id']);
    } else {
      $LAST_PERSONID = Increment::insert([
        'type' => 'PERSONID',
        "description" => 'E900000001',
        "value" => 900000001
      ])['value'];
    }

    if ($TRANSACTIONID) {
      $LAST_TRANSACTIONID = $TRANSACTIONID['value'] + 1;
      Increment::update([
        "description" => 'E'.$LAST_TRANSACTIONID,
        "value" => $LAST_TRANSACTIONID
      ], $TRANSACTIONID['id']);
    } else {
      $LAST_TRANSACTIONID = Increment::insert([
        'type' => 'TRANSACTIONID',
        "description" => 'E900000001',
        "value" => 900000001
      ])['value'];
    }

    return $LAST_TRANSACTIONID;
  }
}
