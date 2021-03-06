<?php

namespace App\Http\Services\Customers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Http\Services\Utils\CurrencyFormat;
use App\Http\Services\History\TransHistoryService;
use App\Model\Accurate\PERSONDATA;

class CustomerService extends BaseController
{
  public static function getAll($query, $FIELDS = null) {
    $model = new PERSONDATA();
    $query['PERSONTYPE'] = 0;
    $query['SUSPENDED'] = 0;
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }

  public static function findById($id, $FIELDS = null) {
    return PERSONDATA::select($FIELDS ?: '*')
    ->where('ID', $id)->first();
  }

  public static function insert($request) {
    // CREATE TRANSACTION
    $TRANSHISTORY = TransHistoryService::create([
      "TRANSTYPE" => 45, // ID PERSONDATA / Customer
      "BRANCHCODEID" => 1,
      "STATUS" => 1,
      "USERID" => 0
    ]);

    if ($TRANSHISTORY) {
      $PERSONNO = Increment::createNewNumber("PERSONNO");
      $PERSONID = Increment::GETPERSONID();
      $TRANSACTIONID = $TRANSHISTORY[0]->TRANSACTIONID;
      
      try {
        \DB::beginTransaction();
        // CREATE USER CUSTOMER
        $insert = new PERSONDATA();
        $insert->ID = $PERSONID;
        $insert->PERSONNO = env('TYPE_NUMBER').$PERSONNO;
        $insert->PERSONTYPE = 0;
        $insert->NAME = isset($request['name']) ? strtoupper($request['name']) : null;
        $insert->PHONE = isset($request['phone']) ? $request['phone'] : null;
        $insert->EMAIL = isset($request['email']) ? $request['email']: null;
        $insert->ADDRESSLINE1 = isset($request['address']) ? $request['address'] : null;
        $insert->PRICELEVEL = isset($request['priceLevel']) ? $request['priceLevel'] : null;
        $insert->CITY = isset($request['city']) ? $request['city'] : null;
        $insert->CURRENCYID = 1;
        $insert->TRANSACTIONID = $TRANSACTIONID;
        $insert->TERMSID = 1;
        $insert->CUSTOMERTYPEID = 1;
        $insert->BRANCHCODEID = 1;
        $insert->CREDITLIMITDAYS = isset($request['creaditLimitDays']) ? $request['creaditLimitDays'] : null;
        $insert->CREDITLIMIT = isset($request['creaditLimit']) ? CurrencyFormat::ABS($request['creaditLimit']) : 0;
        $insert->save();
    
        \DB::commit();
        return $insert;
      } catch (Exception $e) {
        \DB::rollback();
        return false;
      }
    }
    return false;
  }

  public static function update($id, $request) {
    $update = PERSONDATA::find($id);
    $update->NAME = isset($request['name']) ? strtoupper($request['name']) : null;
    $update->PHONE = isset($request['phone']) ? $request['phone'] : null;
    $update->EMAIL = isset($request['email']) ? $request['email']: null;
    $update->ADDRESSLINE1 = isset($request['address']) ? $request['address'] : null;
    $update->PRICELEVEL = isset($request['priceLevel']) ? $request['priceLevel'] : null;
    $update->CITY = isset($request['city']) ? $request['city'] : null;
    $update->CREDITLIMITDAYS = isset($request['creaditLimitDays']) ? $request['creaditLimitDays'] : null;
    $update->CREDITLIMIT = isset($request['creaditLimit']) ? CurrencyFormat::ABS($request['creaditLimit']) : 0;
    $update->save();

    if ($update) {
      return $update;
    }
    return false;
  }

  public static function delete($id) {
    $delete = PERSONDATA::where('ID', $id)->delete();
    if ($delete) return $delete;
    return false;
  }

  public static function count($query, $FIELDS = null) {
    $model = new PERSONDATA();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}
