<?php

namespace App\Http\Services\Transaction;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Http\Services\Utils\TimeService;
use App\Http\Services\Utils\CurrencyFormat;
use App\Http\Services\Customers\CustomerService;
use App\Http\Services\Transaction\GLHISTService;
use App\Http\Services\Items\ItemService;
use App\Http\Services\History\TransHistoryService;
use App\Model\Accurate\ARINV;
use App\Model\Accurate\ARINVDET;
use DB;

class ArinvService extends BaseController
{
  public static function insertArinv ($data) {
    try {
      $CURRENTDATE = TimeService::getCurrentDate();
      $CURRENTYEAR = TimeService::getCurrentYear();
      $CURRENTMOUNT = TimeService::getCurrentMounth();
      $CUSTOMER = CustomerService::findById($data['customerId']);
      
      // CREATE TRANSACTION HISTORY
      $TRANSHISTORY = TransHistoryService::create([
        "TRANSTYPE" => 65, // Faktu Penjualan
        "BRANCHCODEID" => 1,
        "STATUS" => 1,
        "USERID" => 0
      ]);
      
      $freightNominal = isset($data['freightNominal']) ? $data['freightNominal'] : 0;
      $discountNominal = isset($data['discountNominal']) ? $data['discountNominal'] : 0;

      // $dbConn = DB::connection('firebird');
      // $dbConn->beginTransaction();

      $insert = new ARINV();
      $insert->PURCHASEORDERNO = NULL;
      $insert->ARINVOICEID = Increment::GETARINV_ID_NO();
      $insert->CUSTOMERID = $CUSTOMER['ID'];
      $insert->SALESMANID = $data['salesmanId'];
      $insert->INVOICENO = Increment::createNewNumber("INVOICENO"); //invoice penjualan
      $insert->WAREHOUSEID = $data['warehouseId'];
      $insert->INVOICEDATE = $CURRENTDATE;
      $insert->INVOICEAMOUNT = CurrencyFormat::convert(($data['totalBill']+$freightNominal)-$discountNominal);
      $insert->PAIDAMOUNT = CurrencyFormat::convert(0); // paid 0, pembayaran melalui accurate
      $insert->RATE = CurrencyFormat::convert(1);
      $insert->TERMDISCOUNT = CurrencyFormat::convert($discountNominal);
      $insert->RETURNAMOUNT = 0;
      $insert->OWING = CurrencyFormat::convert($insert->INVOICEAMOUNT-$insert->PAIDAMOUNT);
      $insert->TERMSID = 1;
      $insert->GLPERIOD = $CURRENTMOUNT;
      $insert->GLYEAR = $CURRENTYEAR;
      $insert->PRINTED = 0;
      $insert->SHIPDATE = $CURRENTDATE;
      $insert->TAX1RATE = 0;
      $insert->TAX2RATE = 0;
      $insert->GLHISTID = Increment::GETGLHISTID();
      $insert->PAYMENT = 0;
      $insert->CASHDISCOUNT = CurrencyFormat::convert($discountNominal);
      $insert->TEMPLATEID = 20; //20 adalah faktur penjualan
      $insert->ARACCOUNT = $data['accountReceivable']; // GLACCOUNT penjualan
      $insert->GETFROMOTHER = 0;
      $insert->DELIVERYORDER = 0;
      $insert->GETFROMSO = 0;
      $insert->FISCALRATE = 10000;
      $insert->OWINGDC = 0;
      $insert->TAX1AMOUNT = 0;
      $insert->TAX2AMOUNT = 0;
      $insert->INCLUSIVETAX = 0;
      $insert->CUSTOMERISTAXABLE = 0;
      $insert->GETFROMDO = 0;
      $insert->FREIGHT = CurrencyFormat::convert($freightNominal);
      $insert->FREIGHTACCNT = $freightNominal ? $data['accountFreight'] : null;
      $insert->RECONCILED = 0;
      $insert->INVFROMSR = 0;
      $insert->TAXDATE = $CURRENTDATE;
      $insert->REPORTEDTAX1 = 0;
      $insert->REPORTEDTAX2 = 0;
      $insert->ROUNDEDTAX1AMOUNT = 0;
      $insert->ROUNDEDTAX2AMOUNT = 0;
      $insert->ISTAXPAYMENT = 0;
      $insert->TRANSACTIONID = $TRANSHISTORY[0]->TRANSACTIONID; //transaction id
      $insert->SHIPTO1 = $CUSTOMER['NAME'];
      $insert->SHIPTO2 = $CUSTOMER['ADDRESSLINE1'];
      $insert->SHIPTO3 = $CUSTOMER['CITY'];
      $insert->SHIPTO4 = $CUSTOMER['PHONE'];
      $insert->BRANCHCODEID = 1;
      $insert->GETFROMQUOTE = 0;
      $insert->TAXRETURNAMOUNT = 0;
      $insert->RETURNNOTAX = 0;
      $insert->INVAMTBEFORETAX = CurrencyFormat::convert($insert->INVOICEAMOUNT);
      $insert->TAXDISCPAYMENT = 0;
      $insert->DISCPAYMENT = 0;
      $insert->TAXPAIDAMOUNT = 0;
      $insert->BASETAXINVAMT = 0;
      $insert->ISOUTSTANDING = $insert->PAIDAMOUNT >= $insert->INVOICEAMOUNT ? 0 : 1;
      $insert->OUTSTANDINGDO = 1;
      $insert->MAXCHEQUEDATE = $CURRENTDATE; //jika belum lunas NULL
      $insert->RATETYPE = 0;
      $insert->TAX1AMOUNTDP = 0;
      $insert->TAX2AMOUNTDP = 0;
      $insert->ROUNDEDTAX1DP = 0;
      $insert->ROUNDEDTAX2DP = 0;
      $insert->PPH23AMOUNT = 0;
      $insert->COGSAMOUNT = 0;
      $insert->DPAMOUNT = 0;
      $insert->DPTAX = 0;
      $insert->PROJECTAMOUNT = 0;
      $insert->save();
      
      if ($insert) {
        $seq = 0;
        GLHISTService::insert([
          'seq' => $seq,
          'account' => $data['accountReceivable'],
          'totalPayment' => $insert['INVAMTBEFORETAX'],
          'description' => 'Faktur Penjualan from External : '.$insert['INVOICENO'],
          'invoiceId' => $insert['ARINVOICEID'],
          'customerId' => $data['customerId'],
          'salesmanId' => $data['salesmanId']
        ]);

        if ($insert['CASHDISCOUNT'] > 0) {
          $seq += 1;
          GLHISTService::insert([
            'seq' => $seq,
            'account' => $data['accountTermDiscount'],
            'totalPayment' => $insert['INVAMTBEFORETAX'],
            'description' => 'Faktur Penjualan from External : '.$insert['INVOICENO'],
            'invoiceId' => $insert['ARINVOICEID'],
            'customerId' => $data['customerId'],
            'salesmanId' => $data['salesmanId']
          ]);
        }

        if ($freightNominal > 0) {
          $seq += 1;
          GLHISTService::insert([
            'seq' => $seq,
            'account' => $data['accountFreight'],
            'totalPayment' => $insert['INVAMTBEFORETAX'] * (-1),
            'description' => 'Faktur Penjualan from External : '.$insert['INVOICENO'],
            'invoiceId' => $insert['ARINVOICEID'],
            'customerId' => $data['customerId'],
            'salesmanId' => $data['salesmanId']
          ]);
        }
        $seq += 1;
        GLHISTService::insert([
          'seq' => $seq,
          'account' => $data['accountSales'],
          'totalPayment' => $insert['INVAMTBEFORETAX'] * (-1),
          'description' => 'Faktur Penjualan from External : '.$insert['INVOICENO'],
          'invoiceId' => $insert['ARINVOICEID'],
          'customerId' => $data['customerId'],
          'salesmanId' => $data['salesmanId']
        ]);
      }
      return $insert;
      // $dbConn->commit();
    }
    catch (Exception $e) {
      // $dbConn->rollBack();
      return $e;
    }
  }
  
  public static function insertArinvDet ($ARINVOICEID = null, $WAREHOUSEID = null, $data) {
    foreach ($data as $key => $item) {
      $ITEM = ItemService::findById($item['itemNo']);

      $insert = new ARINVDET();
      $insert->ARINVOICEID = $ARINVOICEID;
      $insert->SEQ = $key += 1;
      $insert->ITEMNO = $ITEM['ITEMNO'];
      $insert->ITEMOVDESC = $ITEM['ITEMDESCRIPTION'];
      $insert->ITEMHISTID = NULL;
      $insert->TAXABLEAMOUNT1 = 0;
      $insert->TAXABLEAMOUNT2 = 0;
      $insert->WAREHOUSEID = $WAREHOUSEID;
      $insert->SISTATQTY = 0;
      $insert->DPUSED = 0;
      $insert->ITEMCOSTBASE = CurrencyFormat::convert($item['price']);
      $insert->QUANTITY = CurrencyFormat::convert($item['qty']);
      $insert->UNITPRICE = CurrencyFormat::convert($item['price']);
      $insert->UNITRATIO = CurrencyFormat::convert(1);
      $insert->BRUTOUNITPRICE = CurrencyFormat::convert($item['price']);
      $insert->ITEMCOST = CurrencyFormat::convert($item['price']);
      $insert->save();
    }
    return true;
  }
  
  public static function createTransaction ($data) {
    try {
      $ARINV = ArinvService::insertArinv($data);
      $ARINVDET = ArinvService::insertArinvDet($ARINV['ARINVOICEID'], $ARINV['WAREHOUSEID'], $data['items']);
      return $ARINV;
    } catch (Exception $e) {
      return $e;
    }
  }
  public static function getAll($query, $FIELDS = null) {
    $model = new ARINV();
    $data = Query::Paginate($model, $query, $FIELDS);
    return $data;
  }
  
  public static function findById($id, $FIELDS = null) {
    return ARINV::select($FIELDS ?: '*')
    ->where('INVOICENO', $id)
    ->first();
  }

  public static function count($query = [], $FIELDS = null) {
    $model = new ARINV();
    $count = Query::countData($model, $query, $FIELDS);
    return $count;
  }
}