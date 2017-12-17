<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respond($response, $httpStatus) {
        return new Response($response, $httpStatus);
    }
}
