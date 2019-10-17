<?php

namespace App\Http\Swagger;

use Laravel\Lumen\Routing\Controller as BaseController;

class SwaggerRequest extends BaseController
{
/** 
 * @SWG\Definition(
 *   definition="RequestUser",
 * 	 @SWG\Property(property="firstName", type="string", example="Muhammad"),
 * 	 @SWG\Property(property="lastName", type="string", example="Al-Pandi"),
 * 	 @SWG\Property(property="userLevel", type="number", example=2),
 * 	 @SWG\Property(property="username", type="string", example="pandi95")
 * ),
 * 
 * @SWG\Definition(
 *   definition="RequestTransaction",
 * 	 @SWG\Property(property="customerId", type="number", example=511),
 * 	 @SWG\Property(property="salesmanId", type="number", example=511),
 * 	 @SWG\Property(property="warehouseId", type="number", example=1),
 * 	 @SWG\Property(property="freightNominal", type="number", example=0),
 * 	 @SWG\Property(property="discountPercent", type="number", example=5),
 * 	 @SWG\Property(property="discountNominal", type="number", example=1000),
 *   @SWG\Property(property="items", type="array",
 *     @SWG\Items(
 *       @SWG\Property(property="itemNo", type="string"),
 *       @SWG\Property(property="qty", type="string"),
 *       @SWG\Property(property="price", type="string"),
 *     )
 *   )
 * ),
 */
}