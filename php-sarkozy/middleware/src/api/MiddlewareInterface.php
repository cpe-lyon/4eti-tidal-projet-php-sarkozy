<?php
namespace PhpSarkozy\Middleware\api;

use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\api\Response;

interface MiddlewareInterface{
    function intercept_request(Request $request): Request;
    function intercept_response(Response $response): Response;
}

?>