<?php

namespace PhpSarkozy\core\utils;

use PhpSarkozy\core\api\MiddlewareData;
use PhpSarkozy\core\attributes\Middleware;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\attributes\SarkozyModule;

/**
 * Utils for Middleware gestion
 */
final class MiddlewareUtils
{
    
 
    public static function get_all_middlewares(): array{
        $reflection_middleware = array();
        foreach( get_declared_classes() as $class){
            $reflection_class = new \ReflectionClass($class);
            if ( $attributes = $reflection_class->getAttributes(Middleware::class, \ReflectionAttribute::IS_INSTANCEOF)){
                if(count($attributes) == 1){
                    if($priority = current($attributes[0]->getArguments())){
                        $obj = ["priority"=>$priority,"reflection_class"=>$reflection_class, "interfacesNames" => $reflection_class->getInterfaceNames()];
                    }else{
                        $obj = ["priority"=> 1,"reflection_class"=>$reflection_class, "interfacesNames" => $reflection_class->getInterfaceNames()];
                    }
                    
                    $reflection_middleware[] = $obj;
                }
            }
        }

        return $reflection_middleware;
    }

    public static function intercept_request(Request &$request, $modules): ?MiddlewareData{
        if(($middlewares_module = $modules[SarkozyModule::MIDDLEWARE_MODULE]) !== NULL){
            return $middlewares_module->intercept_request($request);
        }
    }

    public static function intercept_response(Request $request, $modules, ?MiddlewareData $data){
        $response = $request->get_response();
        if(($middlewares_module = $modules[SarkozyModule::MIDDLEWARE_MODULE]) !== NULL){
             $middlewares_module->intercept_response($response, $data);
        }
    }
}


?>