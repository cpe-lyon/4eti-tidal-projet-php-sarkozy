<?php

namespace PhpSarkozy\core\api;

class SarkontrollerRequest
{

    public $controllerIndex;
    public $controllerMethod;
    public $args;

    function __construct($controllerIndex, string $controllerMethod, array $args){
        $this->$controllerIndex = $controllerIndex;
        $this->$controllerMethod = $controllerMethod;
        $this->args = $args;
    }
}

?>