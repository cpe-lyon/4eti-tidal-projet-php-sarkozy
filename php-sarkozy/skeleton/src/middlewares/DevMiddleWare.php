<?php

use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\attributes\Middleware;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\Middleware\api\MiddlewareInterface;


#[Middleware]
class DevMiddleWare implements MiddlewareInterface{


    private $dev_mode = false;
    private HttpRequest $request;

    function intercept_request(Request $req): Request{
        return $req;
    }



    function intercept_response(Response $response): Response{
        var_dump($response->get_metadata()["headers"]);

        return $response;
    }
}