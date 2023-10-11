<?php

namespace PhpSarkozy\core\api;

class SarkontrollerRequest
{

    public $controller_index;
    public $controller_method;
    public $args;

    function __construct($controller_index, string $controller_method, array $args){
        $this->controller_index = $controller_index;
        $this->controller_method = $controller_method;
        $this->args = $args;
    }
}

?>