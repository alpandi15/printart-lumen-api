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
 */
}