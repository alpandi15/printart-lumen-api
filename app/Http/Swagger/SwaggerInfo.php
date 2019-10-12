<?php

namespace App\Http\Swagger;

use Laravel\Lumen\Routing\Controller as BaseController;

class SwaggerInfo extends BaseController
{
  /**
   * @SWG\Swagger(
   *     basePath="/",
   *     schemes={"http"},
   *     @SWG\Info(
   *         version="1.0.0",
   *         title="API documentation Lumen",
   *         @SWG\Contact(
   *             email="hudaparodi@gmail.com"
   *         ),
   *     )
   * )
   */
}