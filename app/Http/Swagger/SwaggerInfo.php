<?php

namespace App\Http\Swagger;

use Laravel\Lumen\Routing\Controller as BaseController;

class SwaggerInfo extends BaseController
{
  /**
   * @SWG\Swagger(
   *     basePath="/",
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
   * )
   */
}