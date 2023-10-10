<?php

namespace PhpSarkozy\routing;

use Exception;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\models\HttpControllerMethodRecord;
use PhpSarkozy\Http\models\HttpControllerRecord;
use PhpSarkozy\Http\models\HttpRouterInterface;
use PhpSarkozy\Http\utils\HttpMethodsEnum;
use PhpSarkozy\routing\attributes\HttpPath;

class HttpRouter extends HttpRouterInterface{



    /**
     * @return HttpPath|false
     */
    private function get_method_path(HttpControllerMethodRecord $record){
        return array_filter($record->attributes, fn($a) => $a instanceof HttpPath);
    }

    private function get_paths_controller(int $idx, HttpControllerRecord $controller){
        $methods = $controller->methods;

        $pathed_methods = array_map(fn($m) => array(
            "method" => $m, 
            "path" => $this->get_method_path($m), 
            "idx" => $idx), $methods);
        return array_filter($pathed_methods,
            fn($arr) => $arr["path"] != false
        );

    }

    /**
     * @var array<HttpMethodsEnum,HttpPathCompiler[]>
     */
    private array $path_compilers;

    /**
     * @param HttpControllerRecord[] $controllers;
     */
    function __construct(array $controllers)
    {
        $pathed_methods = array();
        foreach ($controllers as $idx => $controller) {
            array_merge($pathed_methods, $this->get_paths_controller($idx, $controller));
        }

        usort($pathed_methods, fn($p1, $p2) => $p1["path"]->priority - $p2["path"]->priority);
        $path_compilers = array(
            HttpMethodsEnum::GET => array(),
            HttpMethodsEnum::POST => array(),
            HttpMethodsEnum::PUT => array(),
            HttpMethodsEnum::DELETE => array()
        );

        foreach ($pathed_methods as $p) {
            array_push($path_compilers[$p["path"]->method],  new HttpPathCompiler($p["idx"], $p["method"], $p["path"]));
        }

        $this->path_compilers = $path_compilers;

    
    }

    function get_call(string $path,  HttpMethodsEnum $method): SarkontrollerRequest{
        
        $sk_request = null;
        $iter = new \ArrayIterator($this->path_compilers[$method]);

        $raw_path = "";
        $req_args = array();

        while($sk_request == null && $iter->valid()){
            $iter->current()->compile($raw_path, $req_args);
            $iter->next();
        }
        if ($sk_request == null){
            throw new Exception("Route not found", 404);
        }
        return $sk_request;
    }
}