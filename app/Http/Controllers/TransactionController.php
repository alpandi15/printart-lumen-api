<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Transaction\ArinvService as Service;
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
  
  public static function createTransaction (Request $request) {
    return Service::insertArinv($request);
  }
  
}