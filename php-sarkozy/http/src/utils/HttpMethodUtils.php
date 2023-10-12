<?php

namespace PhpSarkozy\Http\utils;

final class HttpMethodUtils{
    public static function parse_method(string $method): HttpMethodsEnum{
        switch( strtolower($method) ){
            case "get":
            default:
                return HttpMethodsEnum::GET;
            case "post":
                return HttpMethodsEnum::POST;
            case "put":
                return HttpMethodsEnum::PUT;
            case "delete":
                return HttpMethodsEnum::DELETE;
        }
    }

    public static function has_body(HttpMethodsEnum $method){
        return $method == HttpMethodsEnum::POST || $method == HttpMethodsEnum::PUT;
    }
}