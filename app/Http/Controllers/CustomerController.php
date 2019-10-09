<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Customers\CustomerService;

class CustomerController extends BaseController
{
  function searchCustomer(Request $request) {
    $data = CustomerService::getAll($request->all());
    return response()->json($data);
  }
}
