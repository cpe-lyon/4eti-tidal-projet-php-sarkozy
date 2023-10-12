<?php

namespace PhpSarkozy\Http\utils;

use Exception;
use PhpSarkozy\core\attributes\Sarkontroller;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\attributes\HttpAttributeInterface;
use PhpSarkozy\Http\attributes\HttpProduces;
use PhpSarkozy\Http\models\HttpControllerRecord;

/**
 * Utils for Http Attributes 
 */
final class HttpAttributesUtils
{ 
    /**
     * @param HttpControllerRecord[] $records
     * @return HttpAttributeInterface[]
     */
    public static function get_attributes(HttpRequest $request, array $records ): array{
        $call = $request->call;
        if ($call == null){
            throw new Exception("Controller request is null", 500);
        }
        $record = $records[$call->controller_index] ?? null;
        if ($record == null){
            throw new Exception("Controller request method is null", 500);
        }
        $method = $record->methods[$call->controller_method] ?? null;
        if ($method == null){
            throw new Exception("Controller request method is null", 500);
        }

        return $method->attributes;
    }

    public static function get_http_produces(HttpRequest $request, array $records ): ?HttpProduces{

        $attrs = HttpAttributesUtils::get_attributes($request, $records);
        foreach ($attrs as $attr){
            if($attr instanceof HttpProduces){
                return $attr;
            }
        }
        return null;
    }

    public static function filter_attrs(HttpRequest $request, array $records, callable $filter ): array{
        $attrs = HttpAttributesUtils::get_attributes($request, $records);
        return array_filter($attrs, $filter);
    }
}


?>