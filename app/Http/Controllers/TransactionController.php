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
    $data = $request->all();
    $validator = \Validator::make($data, [
        'customerId' => 'required'
    ]);
    
    if ($validator->fails()) {
      return ResponseService::ApiError(422, [
        "message"=>"Error Request"
      ], $validator->errors());
    }

    [ "items" => $items ] = $data;
    $detail = array_map(function ($item) {
      return [
        "qty" => $item['qty'],
        "price" => $item['price'],
        "itemNo" => $item['itemNo'],
        "total" => $this->getTotalAmount($item)
      ];
    }, $items);

    $data['totalBill'] = $this->getTotalDpp($detail);
    $data['discountNominal'] = $data['discountNominal'] + ($data['discountPercent'] / 100) * $data['totalBill'];
    
    return $ARINV = Service::insertArinv($data);
    $ARINVDET = Service::insertArinvDet($ARINV['ARINVOICEID'], $data['warehouseId'], $detail);

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