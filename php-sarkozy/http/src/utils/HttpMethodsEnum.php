<?php

namespace PhpSarkozy\Http\utils;

enum HttpMethodsEnum: string{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}