<?php

namespace PhpSarkozy\HttpRouting;

use PhpSarkozy\Http\models\HttpControllerMethodRecord;
use PhpSarkozy\HttpRouting\attributes\HttpPath;

class HttpPathCompiler{


    private int $controller_index;

    private HttpControllerMethodRecord $method;

    private string $pattern;

    public function __construct(int $controller_index, HttpControllerMethodRecord $method, HttpPath $src_path){
        $this->controller_index = $controller_index;
        $this->method = $method;
        $this->pattern = $src_path->get_path_regex();
    }

    public function compile(string $raw_path, array $req_args){
        $path_args = array();
        if (preg_match($this->pattern, $raw_path, $path_args) == false){
            return null;
        }
        return new HttpCompiledPath($this->controller_index, $this->method, $path_args, $req_args);
    }

}