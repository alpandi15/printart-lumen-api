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
   * )
   */
}