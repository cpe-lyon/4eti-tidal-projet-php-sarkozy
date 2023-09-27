<?php

namespace PhpSarkozy\Http\utils;

use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\attributes\HttpProduces;

/**
 * Utils for Http Attributes 
 */
final class HttpAttributesUtils
{ 
    public static function get_http_produces(HttpRequest $request){
        $attributes = $request->get_attributes();
        foreach($attributes as $attribute){
            if($attribute instanceof HttpProduces){
                return $attribute;
            }
        }
        return null;
    }
}


?>