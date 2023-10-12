<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\core\api\SarkontrollerRequest;

class HttpSarkontrollerRequest extends SarkontrollerRequest{
    

    private HttpControllerMethodRecord $method;

    function get_method_record():HttpControllerMethodRecord{
        return $this->method; 
    }

    function __construct($controller_index, HttpControllerMethodRecord $method, array $args)
    {
        
        parent::__construct($controller_index, $method->method_name, $args);
        $this->method = $method;
    }

}