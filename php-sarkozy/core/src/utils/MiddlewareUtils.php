<?php

namespace PhpSarkozy\core\utils;

use PhpSarkozy\core\attributes\Middleware;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\attributes\SarkozyModule;

/**
 * Utils for Middleware gestion
 */
final class MiddlewareUtils
{
    
 
    public static function get_all_middlewares(): array{
        $reflectionMiddleware = array();
        foreach( get_declared_classes() as $class){
            $reflectionClass = new \ReflectionClass($class);
            if ( $attributes = $reflectionClass->getAttributes(Middleware::class, \ReflectionAttribute::IS_INSTANCEOF)){
                if(count($attributes) == 1){
                    if($priority = $attributes[0]->getArguments()[0]){
                        $obj = ["priority"=>$priority,"reflection_class"=>$reflectionClass, "interfacesNames" => $reflectionClass->getInterfaceNames()];
                    }else{
                        $obj = ["priority"=> 1,"reflection_class"=>$reflectionClass, "interfacesNames" => $reflectionClass->getInterfaceNames()];
                    }
                    
                    $reflectionMiddleware[] = $obj;
                }
            }
        }

        return $reflectionMiddleware;
    }

    public static function intercept_request(Request $request, $modules){
        if(($middlewares_module = $modules[SarkozyModule::MIDDLEWARE_MODULE]) !== NULL){
            $middlewares_module->intercept_request($request);
        }
    }

    public static function intercept_response(Request $request, $modules){
        if(($middlewares_module = $modules[SarkozyModule::MIDDLEWARE_MODULE]) !== NULL){
            $middlewares_module->intercept_response($request->get_response());
        }
    }
}


?>