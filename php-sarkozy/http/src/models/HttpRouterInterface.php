<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\utils\HttpMethodsEnum;

interface HttpRouterInterface{
    function get_call(string $path, HttpMethodsEnum $method): SarkontrollerRequest;
}