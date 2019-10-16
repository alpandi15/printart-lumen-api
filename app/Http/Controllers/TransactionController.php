<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Transaction\ArinvService as Service;
use App\Http\Services\Items\ItemService;
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
          'accountSales' => 'required',
          'accountReceivable' => 'required',
          'accountFreight' => 'required',
          'accountTermDiscount' => 'required',
          'items' => 'required'
      ]);
      
      if ($validator->fails()) {
        return ResponseService::ApiError(422, [
          "message"=>"Error Request"
        ], $validator->errors());
      }
  
      [ "items" => $items ] = $data;
      $discountPercent = isset($data['discountPercent']) ? $data['discountPercent'] : 0;
      $discountNominal = isset($data['discountNominal']) ? $data['discountNominal'] : 0;

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