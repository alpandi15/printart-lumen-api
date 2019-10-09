<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Customers\CustomerService as Service;

class CustomerController extends BaseController
{
  function findAll(Request $request) {
    $data = Service::getAll($request->all());
    return response()->json($data, 200);
  }

  function create(Request $request) {
    $create = Service::insert($request->all());
    return response()->json($create, 200);
  }
}
