<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Transaction\ArinvService as Service;
use App\Http\Services\Items\ItemService;
use App\Http\Services\Account\AccountService;
use App\Http\Services\GLAccount\GLAccountService;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class TransactionController extends BaseController
{
  private $fillable = [
    'TERMID',
    'DISCPC',
    'DISCDAYS',
    'NETDAYS',
    'TERMNAME',
    'COD',
    'TERMMEMO'
  ];
  
  public function createTransaction (Request $request) {
    try {
      $data = $request->all();
      $validator = \Validator::make($data, [
          'customerId' => 'required',
          'salesmanId' => 'required',
          'warehouseId' => 'required',
          'items' => 'required'
      ]);
      
      if ($validator->fails()) {
        return ResponseService::ApiError(422, [
          "message"=>"Error Request"
        ], $validator->errors());
      }
      
      // initial
      [ "items" => $items ] = $data;
      $accountReceivable = null;
      $accountSales = null;
      $accountFreight = null;
      $accountTermDiscount = null;
      $discountPercent = isset($data['discountPercent']) ? $data['discountPercent'] : 0;
      $discountNominal = isset($data['discountNominal']) ? $data['discountNominal'] : 0;

      // check account
      $checkAccount = AccountService::count();
      $checkAccReceivable = 0;
      if ($checkAccount === 0) {
        return ResponseService::ApiError(422, "Account not found, run 'php artisan db:seed' in terminal"); 
      }

      $checkAccReceivable = AccountService::findById('ACCOUNT_RECEIVABLE')['value'];
      $checkAccSales = AccountService::findById('ACCOUNT_SALES')['value'];
      $checkAccDisc = AccountService::findById('ACCOUNT_SALES_TERM_DISC')['value'];
      $checkAccFreight = AccountService::findById('ACCOUNT_FREIGHT')['value'];
      
      // check account receivable
      if(!$checkAccReceivable) {
        return ResponseService::ApiError(422, "The ACCOUNT_RECEIVABLE is not set");
      }
      if (!GLAccountService::findById($checkAccReceivable)) {
        return ResponseService::ApiError(422, "ACCOUNT_RECEIVABLE not found in accurate, please setting account corresponding on accurate");
      }
      $accountReceivable = $checkAccReceivable;

      // check account sales
      if(!$checkAccDisc) {
        return ResponseService::ApiError(422, "The ACCOUNT_SALES_TERM_DISC is not set");
      }
      if (!GLAccountService::findById($checkAccDisc)) {
        return ResponseService::ApiError(422, "ACCOUNT_SALES_TERM_DISC not found in accurate, please setting account corresponding on accurate");
      }
      $accountTermDiscount = $checkAccDisc;

      // check account term discount
      if(!$checkAccSales) {
        return ResponseService::ApiError(422, "The ACCOUNT_SALES is not set");
      }
      if (!GLAccountService::findById($checkAccSales)) {
        return ResponseService::ApiError(422, "ACCOUNT_SALES not found in accurate, please setting account corresponding on accurate");
      }
      $accountSales = $checkAccSales;

      // check account freight
      if(!$checkAccFreight) {
        return ResponseService::ApiError(422, "The ACCOUNT_FREIGHT is not set");
      }
      if (!GLAccountService::findById($checkAccFreight)) {
        return ResponseService::ApiError(422, "ACCOUNT_FREIGHT not found in accurate, please setting account corresponding on accurate");
      }
      $accountFreight = $checkAccFreight;

      $detail = array_map(function ($item) {
        return [
          "qty" => $item['qty'],
          "price" => $item['price'],
          "itemNo" => $item['itemNo'],
          "total" => $this->getTotalAmount($item)
        ];
      }, $items);
      
      $data['totalBill'] = $this->getTotalDpp($detail);
      $data['discountNominal'] = $discountNominal + ($discountPercent / 100) * $data['totalBill'];
      $data['accountReceivable'] = $accountReceivable;
      $data['accountSales'] = $accountSales;
      $data['accountFreight'] = $accountFreight;
      $data['accountTermDiscount'] = $accountTermDiscount;

      $ARINV = Service::insertArinv($data);
      $ARINVDET = Service::insertArinvDet($ARINV['ARINVOICEID'], $data['warehouseId'], $detail);
      if ($ARINVDET) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
        ], $ARINV);
      }
      return ResponseService::ApiError(404, "Failed create transaction");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
  
  public function getTotalAmount($item) {
    $total = 0;
    if ($item) {
      $total = $item['qty'] * $item['price'];
      return $total;
    }
    return $total;
  }

  public function getTotalDpp($item) {
    return $total = array_reduce($item, function ($prev, $next) {
      return $prev + $this->getTotalAmount($next);
    });
  }
}