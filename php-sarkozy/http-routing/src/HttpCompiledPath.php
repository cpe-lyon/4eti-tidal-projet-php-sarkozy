<?php

namespace PhpSarkozy\HttpRouting;

use Exception;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\models\HttpControllerMethodRecord;
use PhpSarkozy\HttpRouting\attributes\HttpInPath;

class HttpCompiledPath extends SarkontrollerRequest{



    private function parse_param(string $pname, array $pattrs, bool $optional, array $path_matches, array $request_args , array &$target){
        $in_path_arr = array_filter( 
            $pattrs,
            fn($attr)=> ($attr instanceof HttpInPath)
        );
        if (count($in_path_arr) == 1){
            /**
             * @var HttpInPath
             */
            $in_path = $in_path_arr[0];
            $src_name = $in_path->src_name;

            if (!isset($path_matches[$src_name])){
                throw new Exception("HttpInPath used with an uncaptured source name: $src_name", 500);
            }
            $target[$pname] = $path_matches[$src_name];


        }else{
            //Not in path
            if (isset($request_args[$pname])){
                $target[$pname] = $request_args[$pname];
            }else if (!$optional){
                throw new Exception("$pname is needed", 420);
            }

        }

        
    }

    function __construct(int $controller_index, HttpControllerMethodRecord $method, array $path_matches, array $request_args){
        
        $args = array();
        foreach ($method->params as $param) {
            $this->parse_param(
                $param[0], $param[1], $param[2], $path_matches, $request_args, $args
            );
        }

        parent::__construct($controller_index, $method->method_name, $args);
    }


}