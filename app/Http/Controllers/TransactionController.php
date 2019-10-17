<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\Transaction\ArinvService as Service;
use App\Http\Services\Account\AccountService;
use App\Http\Services\GLAccount\GLAccountService;
use App\Http\Services\Utils\Query;
use App\Http\Services\Utils\ErrorHandlingService as ResponseService;

class TransactionController extends BaseController
{
  private $fillable = [
    'PURCHASEORDERNO',
    'ARINVOICEID',
    'CUSTOMERID',
    'SALESMANID',
    'INVOICENO',
    'WAREHOUSEID',
    'INVOICEDATE',
    'INVOICEAMOUNT',
    'PAIDAMOUNT',
    'RATE',
    'TERMDISCOUNT',
    'RETURNAMOUNT',
    'OWING',
    'TERMSID',
    'GLPERIOD',
    'GLYEAR',
    'PRINTED',
    'SHIPDATE',
    'TAX1RATE',
    'TAX2RATE',
    'GLHISTID',
    'PAYMENT',
    'CASHDISCOUNT',
    'TEMPLATEID',
    'ARACCOUNT',
    'GETFROMOTHER',
    'DELIVERYORDER',
    'GETFROMSO',
    'FISCALRATE',
    'OWINGDC',
    'TAX1AMOUNT',
    'TAX2AMOUNT',
    'INCLUSIVETAX',
    'CUSTOMERISTAXABLE',
    'GETFROMDO',
    'FREIGHT',
    'RECONCILED',
    'INVFROMSR',
    'TAXDATE',
    'REPORTEDTAX1',
    'REPORTEDTAX2',
    'ROUNDEDTAX1AMOUNT',
    'ROUNDEDTAX2AMOUNT',
    'ISTAXPAYMENT',
    'TRANSACTIONID',
    'SHIPTO1',
    'SHIPTO2',
    'SHIPTO3',
    'SHIPTO4',
    'BRANCHCODEID',
    'GETFROMQUOTE',
    'TAXRETURNAMOUNT',
    'RETURNNOTAX',
    'INVAMTBEFORETAX',
    'TAXDISCPAYMENT',
    'DISCPAYMENT',
    'TAXPAIDAMOUNT',
    'BASETAXINVAMT',
    'ISOUTSTANDING',
    'OUTSTANDINGDO',
    'MAXCHEQUEDATE',
    'RATETYPE',
    'TAX1AMOUNTDP',
    'TAX2AMOUNTDP',
    'ROUNDEDTAX1DP',
    'ROUNDEDTAX2DP',
    'PPH23AMOUNT',
    'COGSAMOUNT',
    'DPAMOUNT',
    'DPTAX',
    'PROJECTAMOUNT'
  ];
  
  /**
  * @SWG\Post(
  *   path="/transaction",
  *   summary="Create Transaction to Accurate",
  *   tags={"Transaction"},
  * 	operationId="create",
  *   @SWG\Parameter(
  *     name="Request",
  *     description="Input request",
  *     in="body",
  *     required=true,
  *     @SWG\Schema(ref="#/definitions/RequestTransaction")
  *   ),
  *   security = { { "Bearer": {} } },
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", ref="$/definitions/Transaction")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function createTransaction (Request $request) {
    try {
      $data = $request->all();
      $validator = \Validator::make($data, [
          'customerId' => 'required',
          'salesmanId' => 'required',
          'warehouseId' => 'required',
          'items' => 'required'
      ]);
      
      if ($validator->fails()) {
        return ResponseService::ApiError(422, [
          "message"=>"Error Request"
        ], $validator->errors());
      }
      
      // initial
      [ "items" => $items ] = $data;
      $accountReceivable = null;
      $accountSales = null;
      $accountFreight = null;
      $accountTermDiscount = null;
      $discountPercent = isset($data['discountPercent']) ? $data['discountPercent'] : 0;
      $discountNominal = isset($data['discountNominal']) ? $data['discountNominal'] : 0;

      // check account
      $checkAccount = AccountService::count();
      
      if ($checkAccount === 0) {
        return ResponseService::ApiError(422, "Account not found, run 'php artisan db:seed' in terminal"); 
      }

      $checkAccReceivable = AccountService::findById('ACCOUNT_RECEIVABLE')['value'];
      $checkAccSales = AccountService::findById('ACCOUNT_SALES')['value'];
      $checkAccDisc = AccountService::findById('ACCOUNT_SALES_TERM_DISC')['value'];
      $checkAccFreight = AccountService::findById('ACCOUNT_FREIGHT')['value'];
      
      // check account receivable
      if(!$checkAccReceivable) {
        return ResponseService::ApiError(422, "The ACCOUNT_RECEIVABLE is not set");
      }
      if (!GLAccountService::findById($checkAccReceivable)) {
        return ResponseService::ApiError(422, "ACCOUNT_RECEIVABLE not found in accurate, please setting account corresponding on accurate");
      }
      $accountReceivable = $checkAccReceivable;

      // check account sales
      if((isset($data['discountPercent']) || isset($data['discountNominal'])) && !$checkAccDisc) {
        return ResponseService::ApiError(422, "The ACCOUNT_SALES_TERM_DISC is not set");
      }
      if ((isset($data['discountPercent']) || isset($data['discountNominal'])) && !GLAccountService::findById($checkAccDisc)) {
        return ResponseService::ApiError(422, "ACCOUNT_SALES_TERM_DISC not found in accurate, please setting account corresponding on accurate");
      }
      $accountTermDiscount = $checkAccDisc;

      // check account term discount
      if(!$checkAccSales) {
        return ResponseService::ApiError(422, "The ACCOUNT_SALES is not set");
      }
      if (!GLAccountService::findById($checkAccSales)) {
        return ResponseService::ApiError(422, "ACCOUNT_SALES not found in accurate, please setting account corresponding on accurate");
      }
      $accountSales = $checkAccSales;

      // check account freight
      if(isset($data['freightNominal']) && !$checkAccFreight) {
        return ResponseService::ApiError(422, "The ACCOUNT_FREIGHT is not set");
      }
      if (isset($data['freightNominal']) && !GLAccountService::findById($checkAccFreight)) {
        return ResponseService::ApiError(422, "ACCOUNT_FREIGHT not found in accurate, please setting account corresponding on accurate");
      }
      $accountFreight = $checkAccFreight;

      $detail = array_map(function ($item) {
        return [
          "qty" => $item['qty'],
          "price" => $item['price'],
          "itemNo" => $item['itemNo'],
          "total" => $this->getTotalAmount($item)
        ];
      }, $items);
      
      $data['totalBill'] = $this->getTotalDpp($detail);
      $data['discountNominal'] = $discountNominal + ($discountPercent / 100) * $data['totalBill'];
      $data['accountReceivable'] = $accountReceivable;
      $data['accountSales'] = $accountSales;
      $data['accountFreight'] = $accountFreight;
      $data['accountTermDiscount'] = $accountTermDiscount;
      unset($data['items']);
      $data['items'] = $detail;
      
      // $ARINV = Service::insertArinv($data);
      // $ARINVDET = Service::insertArinvDet($ARINV['ARINVOICEID'], $data['warehouseId'], $detail);
      $create = Service::createTransaction($data);
      if ($create) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
        ], $create);
      }
      return ResponseService::ApiError(404, "Failed create transaction");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
  
  /**
  * @SWG\Get(
  *   path="/transaction",
  *   summary="Find All Transaction with Paginate",
  *   tags={"Transaction"},
  * 	operationId="findAll",
  *   security = { { "Bearer": {} } },
  *   @SWG\Parameter(name="page", description="Page - eg: 1", in="query", type="number"),
  *   @SWG\Parameter(name="limit", description="Limit Data - eg: 10", in="query", type="number"),
  *   @SWG\Parameter(name="keyword", description="Search - eg: Name", in="query", type="string"),
  *   @SWG\Parameter(name="sort", description="Sorting Field - eg: - ID (desc by id)", in="query", type="string"),
  *   @SWG\Parameter(name="type", description="Retrieve data without paginate query - eg: all", in="query", type="string"),
  *   @SWG\Parameter(name="from", description="Retrieve data by date from - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="to", description="Retrieve data by date to - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="field", description="Retrieve data based on the input fields - eg: ID, NAME", in="query", type="string"),
  *   @SWG\Parameter(name="relationship", description="View relationship of data - eg: 0", in="query", type="string"),
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponsePaginate",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", @SWG\Items(ref="#/definitions/Transaction"))
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function findAll(Request $request) {
    try {
      $data = Service::getAll($request->all(), $this->fillable);
      if ($data) {
        $allowed = [
          "keyword",
          "totalData",
          "perPage",
          "lastPage",
          "currentPage"
        ];
        
        $paginate = Query::filterAllowedField($allowed, $data);
        $dataRes = Query::filterDisallowField($data, $paginate);
    
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
          "paginate" => $paginate
        ], $dataRes['data']);
      }
      return ResponseService::ApiError(404, [
        "message"=>"Error"
      ], "Error");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
  
  /**
  * @SWG\Get(
  *   path="/transaction/{id}",
  *   summary="Find Transaction by Invoice Number",
  *   tags={"Transaction"},
  * 	operationId="findOne",
  *   @SWG\Parameter(
  *     name="id",
  *     description="Invoice Number of transaction that needs to be fetched",
  *     in="path",
  *     required=true,
  *     type="string"
  *   ),
  *   security = { { "Bearer": {} } },
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", ref="$/definitions/Transaction")
  *     )
  *   ),
  *   @SWG\Response(response=422,description="Error Response",ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function findOne($id) {
    try {
      $data = Service::findById($id, $this->fillable);
      if ($data) {
        return ResponseService::ApiSuccess(200, [
          "message"=>"Success",
        ], $data);
      }
      return ResponseService::ApiError(404, "Transaction not found");
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }

  /**
  * @SWG\Get(
  *   path="/transaction-count",
  *   summary="Count Transaction Data",
  *   tags={"Transaction"},
  * 	operationId="countData",
  *   security = { { "Bearer": {} } },
  *   @SWG\Parameter(name="keyword", description="Search - eg: Name", in="query", type="string"),
  *   @SWG\Parameter(name="from", description="Retrieve data by date from - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Parameter(name="to", description="Retrieve data by date to - eg: 2019-08-01", in="query", type="string"),
  *   @SWG\Response(
  *     response=200,
  *     description="Success Response",
  *     ref="$/responses/ApiResponse",
  *     @SWG\Property(property="data", type="object",
  *       @SWG\Property(property="data", type="object",
  *         @SWG\Property(property="count", type="number")
  *       )
  *     )
  *   ),
  *   @SWG\Response(response=422, description="Error Response", ref="$/responses/ApiError"),
  *   @SWG\Response(response=401,description="Unauthorized"),
  *   @SWG\Response(response=404,description="Not Found")
  * )
  */
  function countData(Request $request) {
    try {
      $data = Service::count($request->all(), $this->fillable);
      return ResponseService::ApiSuccess(200, [
        "message"=>"Success"
      ], ["count"=>$data]);
    } catch (Exception $e) {
      return ResponseService::ApiError(422, [
        "message"=>"Error"
      ], $e);
    }
  }
  
  function getTotalAmount($item) {
    $total = 0;
    if ($item) {
      $total = $item['qty'] * $item['price'];
      return $total;
    }
    return $total;
  }

  function getTotalDpp($item) {
    return $total = array_reduce($item, function ($prev, $next) {
      return $prev + $this->getTotalAmount($next);
    });
  }
}