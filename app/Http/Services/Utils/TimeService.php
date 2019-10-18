<?php

namespace App\Http\Services\Utils;
use Laravel\Lumen\Routing\Controller as BaseController;
use DB;

class TimeService extends BaseController
{
  public static function getCurrentDateTime() {
    $data = DB::connection('firebird')->select("select timestamp 'NOW' from rdb".strval("$")."database")[0];
    return $data->CONSTANT;
  }

  public static function getCurrentTime() {
    $data = DB::connection('firebird')->select("select time 'NOW' from rdb".strval("$")."database")[0];
    return $data->CONSTANT;
  }

  public static function getCurrentDate() {
    $data = DB::connection('firebird')->select("select date 'NOW' from rdb".strval("$")."database")[0];
    return $data->CONSTANT;
  }

  public static function getCurrentMounth() {
    return date("m", strtotime(TimeService::getCurrentDate()));
  }

  public static function getCurrentYear() {
    return date("Y", strtotime(TimeService::getCurrentDate()));
  }

  public static function getCurrentDay() {
    return date("d", strtotime(TimeService::getCurrentDate()));
  }
}