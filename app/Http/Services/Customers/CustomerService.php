<?php

namespace App\Http\Services\Customers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Http\Services\History\TransHistoryService;
use App\Model\Accurate\PERSONDATA;

class CustomerService extends BaseController
{
  public static function getAll($query) {
    $model = new PERSONDATA();
    $FIELDS = ['ID','PERSONNO','NAME'];
    $query['PERSONTYPE'] = 0;
    $query['SUSPENDED'] = 0;
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function insert($request) {
    // CREATE TRANSACTION
    $TRANSHISTORY = TransHistoryService::create([
      "TRANSTYPE" => 45,
      "BRANCHCODEID" => 1,
      "STATUS" => 1,
      "USERID" => 0
    ]);

    if ($TRANSHISTORY) {
      $PERSONNO = Increment::createNewNumber("PERSONNO");
      $PERSONID = Increment::GETPERSONID();
      $TRANSACTIONID = $TRANSHISTORY[0]->TRANSACTIONID;
  
      // CREATE USER CUSTOMER
      $insert = new PERSONDATA();
      $insert->ID = $PERSONID;
      $insert->PERSONNO = env('TYPE_NUMBER').$PERSONNO;
      $insert->PERSONTYPE = 0;
      $insert->NAME = strtoupper($request['name']);
      $insert->PHONE = $request['phone'];
      $insert->EMAIL = $request['email'];
      $insert->ADDRESSLINE1 = $request['address'];
      $insert->PRICELEVEL = $request['priceLevel'];
      $insert->CITY = $request['city'];
      $insert->CURRENCYID = 1;
      $insert->TRANSACTIONID = $TRANSACTIONID;
      $insert->TERMSID = 1;
      $insert->CUSTOMERTYPEID = 1;
      $insert->BRANCHCODEID = 1;
      $insert->CREDITLIMITDAYS = $request['creaditLimitDays'];
      $insert->CREDITLIMIT = number_format((float)$request['creaditLimit'], 4, '.', '');
      $insert->save();
  
      return $insert;
    }
    return null;
  }
}
