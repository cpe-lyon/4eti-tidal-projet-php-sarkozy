<?php

namespace PhpSarkozy\HttpRouting;

use Exception;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\models\HttpControllerMethodRecord;
use PhpSarkozy\Http\models\HttpControllerRecord;
use PhpSarkozy\Http\models\HttpRouterInterface;
use PhpSarkozy\Http\utils\HttpMethodsEnum;
use PhpSarkozy\HttpRouting\attributes\HttpPath;

class HttpRouter implements HttpRouterInterface{



    /**
     * @return HttpPath|false
     */
    private function get_method_path(HttpControllerMethodRecord $record){
        return current(array_filter($record->attributes, fn($a) => $a instanceof HttpPath));
    }

    private function get_paths_controller(int $idx, HttpControllerRecord $controller){
        $methods = array_values($controller->methods);

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
            $pathed_methods = array_merge($pathed_methods, $this->get_paths_controller($idx, $controller));
        }

        usort($pathed_methods, fn($p1, $p2) => $p2["path"]->get_priority() - $p1["path"]->get_priority());
        $path_compilers = array(
            HttpMethodsEnum::GET->value => array(),
            HttpMethodsEnum::POST->value => array(),
            HttpMethodsEnum::PUT->value => array(),
            HttpMethodsEnum::DELETE->value => array()
        );

        foreach ($pathed_methods as $p) {
            array_push($path_compilers[$p["path"]->method->value],  new HttpPathCompiler($p["idx"], $p["method"], $p["path"]));
        }

        $this->path_compilers = $path_compilers;

    
    }

    function get_call(string $path,  HttpMethodsEnum $method): SarkontrollerRequest{
        
        $sk_request = null;
        $iter = new \ArrayIterator($this->path_compilers[$method->value]);

        //TODO: parse it
        $raw_path = parse_url($path, PHP_URL_PATH);
        $req_args = array();
        $req_query = parse_url($path, PHP_URL_QUERY);
        parse_str($req_query, $req_args);

        while($sk_request == null && $iter->valid()){
            $sk_request = $iter->current()->compile($raw_path, $req_args);
            $iter->next();
        }
        if ($sk_request == null){
            throw new Exception("Route not found", 404);
        }
        return $sk_request;
    }
}