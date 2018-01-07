<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function emptyHeaderResponse($status = 200, array $headers = []){
        return new Response('', $status, $headers);
    }
}
