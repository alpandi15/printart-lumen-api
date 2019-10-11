<?php

namespace App\Http\Services\Utils;

use Laravel\Lumen\Routing\Controller as BaseController;

class ErrorHandlingService extends BaseController
{
  public static function ApiSuccess($code = 200, $detail = "", $data = "") {
    return response()->json([
      "success" => true,
      "meta" => $detail,
      "data" => $data
    ], $code);
  }

  public static function ApiError($code = "", $message = "", $detail = "") {
    return response()->json([
      "success" => false,
      "meta" => $message,
      "detail" => $detail ? $detail : $message
    ], $code);
  }
}