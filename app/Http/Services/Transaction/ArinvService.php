<?php

namespace App\Http\Services\Transaction;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\Increment;
use App\Model\Accurate\ARINV;
use App\Model\Accurate\ARINVDET;

class ArinvService extends BaseController
{
  public static function insertArinv ($data) {
    return Increment::GETARINV_ID_NO();
  }
}