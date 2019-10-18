<?php

namespace App\Http\Swagger;

use Laravel\Lumen\Routing\Controller as BaseController;

class SwaggerInfo extends BaseController
{
  /**
   * @SWG\Swagger(
   *     basePath="/api/v1",
   *     schemes={"http", "https"},
   *     produces={"application/json", "application/xml"},
   *     @SWG\Info(
   *         version= "1.0.0",
   *         title= "API documentation for Printart Accurate"
   *     ),
   *     @SWG\SecurityScheme(
   *         securityDefinition="Bearer",
   *         type="apiKey",
   *         name="Authorization",
   *         in="header"
   *     ),
   * 
   *    @SWG\Response(
   *        response="ApiResponsePaginate",
   *        description="Success Response",
   *        @SWG\Schema(
   *             type="object",
   *             @SWG\Property(property="success",type="boolean"),
   *             @SWG\Property(property="meta", type="object",
   *                 @SWG\Property(property="message", type="string", example="Success"),
   *                 @SWG\Property(property="paginate", type="object",
   *                    @SWG\Property(property="keyword", type="string"),
   *                    @SWG\Property(property="totalData", type="number"),
   *                    @SWG\Property(property="perPage", type="number"),
   *                    @SWG\Property(property="lastPage", type="number"),
   *                    @SWG\Property(property="currentPage", type="number"),
   *                 )
   *             )
   *        )
   *    ),
   *    @SWG\Response(
   *        response="ApiResponse",
   *        description="Success Response",
   *        @SWG\Schema(
   *             type="object",
   *             @SWG\Property(property="success",type="boolean"),
   *             @SWG\Property(property="meta", type="object",
   *                 @SWG\Property(property="message", type="string", example="Success")
   *             )
   *        )
   *    ),
   *    @SWG\Response(
   *        response="ApiError",
   *        description="Success Response",
   *        @SWG\Schema(
   *             type="object",
   *             @SWG\Property(property="success",type="boolean", example=false),
   *             @SWG\Property(property="meta", type="object",
   *                 @SWG\Property(property="message",type="string")
   *             ),
   *             @SWG\Property(property="detail",type="string")
   *        )
   *    ),
   *
   * 		@SWG\Definition(
   * 			definition="User",
   * 			@SWG\Property(property="USERID", type="number"),
   * 			@SWG\Property(property="USERNAME", type="string"),
   * 			@SWG\Property(property="USERLEVEL", type="string"),
   * 			@SWG\Property(property="FULLNAME", type="string"),
   * 			@SWG\Property(property="USERPASSWORD", type="string")
   * 		),
   *
   * 		@SWG\Definition(
   * 			definition="Item",
   * 			@SWG\Property(property="ITEMNO", type="number"),
   * 			@SWG\Property(property="ITEMDESCRIPTION", type="string"),
   * 			@SWG\Property(property="ITEMTYPE", type="string"),
   * 			@SWG\Property(property="NOTES", type="string"),
   * 			@SWG\Property(property="QUANTITY", type="string"),
   * 			@SWG\Property(property="UNITPRICE", type="string"),
   * 			@SWG\Property(property="UNITPRICE2", type="string"),
   * 			@SWG\Property(property="UNITPRICE3", type="string"),
   * 			@SWG\Property(property="UNITPRICE4", type="string"),
   * 			@SWG\Property(property="UNITPRICE5", type="string")
   * 		),
   *
   * 		@SWG\Definition(
   * 			definition="GLAccount",
   * 			@SWG\Property(property="GLACCOUNT", type="number"),
   * 			@SWG\Property(property="CURRENCYID", type="string"),
   * 			@SWG\Property(property="ACCOUNTNAME", type="string"),
   * 			@SWG\Property(property="ACCOUNTTYPE", type="string"),
   * 			@SWG\Property(property="SUBACCOUNT", type="string"),
   * 			@SWG\Property(property="PARENTACCOUNT", type="string"),
   * 			@SWG\Property(property="SUSPENDED", type="string"),
   * 			@SWG\Property(property="MEMO", type="string"),
   * 			@SWG\Property(property="FIRSTPARENTACCOUNT", type="string"),
   * 			@SWG\Property(property="INDENTLEVEL", type="string"),
   * 			@SWG\Property(property="ISFISCAL", type="string"),
   * 			@SWG\Property(property="ISALLOCTOPROD", type="string"),
   * 			@SWG\Property(property="TRANSACTIONID", type="string"),
   * 			@SWG\Property(property="IMPORTEDTRANSACTIONID", type="string"),
   * 			@SWG\Property(property="BRANCHCODEID", type="string"),
   * 			@SWG\Property(property="LFT", type="string"),
   * 			@SWG\Property(property="RGT", type="string"),
   * 			@SWG\Property(property="ISROOT", type="string"),
   * 			@SWG\Property(property="NEXTINVOICENO", type="string")
   * 		),
   * 
   * 		@SWG\Definition(
   * 			definition="Wirehouse",
   * 			@SWG\Property(property="WAREHOUSEID", type="number"),
   * 			@SWG\Property(property="NAME", type="string"),
   * 			@SWG\Property(property="DESCRIPTION", type="string"),
   * 			@SWG\Property(property="ADDRESS1", type="string"),
   * 			@SWG\Property(property="ADDRESS2", type="string"),
   * 			@SWG\Property(property="ADDRESS3", type="string"),
   * 			@SWG\Property(property="SUSPENDED", type="string")
   * 		),
   * 
   * 		@SWG\Definition(
   * 			definition="AccountPayment",
   * 			@SWG\Property(property="id", type="number"),
   * 			@SWG\Property(property="type", type="string"),
   * 			@SWG\Property(property="description", type="string"),
   * 			@SWG\Property(property="value", type="string"),
   * 			@SWG\Property(property="created_at", type="string"),
   * 			@SWG\Property(property="updated_at", type="string")
   * 		),
   *
   * 		@SWG\Definition(
   * 			definition="Transaction",
   * 			@SWG\Property(property="PURCHASEORDERNO", type="string"),
   * 			@SWG\Property(property="ARINVOICEID", type="string"),
   * 			@SWG\Property(property="CUSTOMERID", type="number"),
   * 			@SWG\Property(property="SALESMANID", type="number"),
   * 			@SWG\Property(property="INVOICENO", type="string"),
   * 			@SWG\Property(property="WAREHOUSEID", type="number"),
   * 			@SWG\Property(property="INVOICEDATE", type="string"),
   * 			@SWG\Property(property="INVOICEAMOUNT", type="number"),
   * 			@SWG\Property(property="PAIDAMOUNT", type="number"),
   * 			@SWG\Property(property="RATE", type="string"),
   * 			@SWG\Property(property="TERMDISCOUNT", type="number"),
   * 			@SWG\Property(property="RETURNAMOUNT", type="number"),
   * 			@SWG\Property(property="OWING", type="number"),
   * 			@SWG\Property(property="TERMSID", type="number"),
   * 			@SWG\Property(property="GLPERIOD", type="string"),
   * 			@SWG\Property(property="GLYEAR", type="string"),
   * 			@SWG\Property(property="PRINTED", type="string"),
   * 			@SWG\Property(property="SHIPDATE", type="string"),
   * 			@SWG\Property(property="TAX1RATE", type="string"),
   * 			@SWG\Property(property="TAX2RATE", type="string"),
   * 			@SWG\Property(property="GLHISTID", type="number"),
   * 			@SWG\Property(property="PAYMENT", type="string"),
   * 			@SWG\Property(property="CASHDISCOUNT", type="string"),
   * 			@SWG\Property(property="TEMPLATEID", type="string"),
   * 			@SWG\Property(property="ARACCOUNT", type="string"),
   * 			@SWG\Property(property="GETFROMOTHER", type="string"),
   * 			@SWG\Property(property="DELIVERYORDER", type="string"),
   * 			@SWG\Property(property="GETFROMSO", type="string"),
   * 			@SWG\Property(property="TRANSACTIONID", type="string"),
   * 			@SWG\Property(property="FREIGHT", type="string"),
   * 			@SWG\Property(property="ISOUTSTANDING", type="string"),
   * 			@SWG\Property(property="OUTSTANDINGDO", type="string"),
   * 			@SWG\Property(property="DPAMOUNT", type="string"),
   * 			@SWG\Property(property="DPTAX", type="string"),
   * 			@SWG\Property(property="PROJECTAMOUNT", type="string"),
   * 		),
   * )
   */
}