<?php

namespace App\Http\Services\History;

use Laravel\Lumen\Routing\Controller as BaseController;
use DB;

class TransHistoryService extends BaseController
{
  public static function create($data) {
    $USERID = $data['USERID'];
    $TRANSTYPE = $data['TRANSTYPE'];
    $BRANCHCODEID = $data['BRANCHCODEID'];
    $STATUS = $data['STATUS'];

    $create = DB::connection('firebird')
    ->select("
      EXECUTE PROCEDURE
      ADDTRANSACTIONSHITORY(
        $TRANSTYPE,
        $BRANCHCODEID,
        NULL,
        $STATUS,
        NULL,
        $USERID
      )"
    );
    return $create;
  }
}