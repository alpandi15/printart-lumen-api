<?php

namespace App\Http\Services\Transaction;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Increment;
use App\Http\Services\Utils\TimeService;
use App\Model\Accurate\GLHIST;

class GLHISTService extends BaseController
{
  public static function insert ($data) {
    $insert = new GLHIST();
    $insert->GLHISTID = Increment::GETGLHISTID();
    $insert->SEQ = $data['seq'];
    $insert->GLACCOUNT = $data['account'];
    $insert->GLYEAR = TimeService::getCurrentYear();
    $insert->GLPERIOD = TimeService::getCurrentMounth();
    $insert->BASEAMOUNT = $data['totalPayment'];
    $insert->PRIMEAMOUNT = $data['totalPayment'];
    $insert->SOURCE = 'AR';
    $insert->TRANSTYPE = 'INV';
    $insert->TRANSDATE = TimeService::getCurrentDate();
    $insert->TRANSDESCRIPTION = $data['description'];
    $insert->INVOICEID = $data['invoiceId'];
    $insert->PERSONID = $data['customerId'];
    $insert->USERID = $data['salesmanId'];
    $insert->save();

    return $insert;
  }
}