<?php

namespace App\Http\Services\Utils;

use Laravel\Lumen\Routing\Controller as BaseController;
use DB;

class CurrencyFormat extends BaseController
{
  public static function ABS($value) {
    $abs = DB::connection('firebird')->select("EXECUTE PROCEDURE ABS($value)");
    return $abs ? $abs[0]->ANUM : 0;
  }

  public static function convert($value) {
    return number_format((float)$value, 4, '.', '');
  }
}