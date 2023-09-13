<?php

namespace PhpSarkozy\core\api;

class SarkoError{

    //DO NOT implement http errors, keep it general for core (for http or not)
    const CUSTOM_MODULE_ERROR = 0;
    const CANT_CALL_CONTROLLER = 1;
    
    public int $origin;
    function __construct(int $origin=SarkoError::CUSTOM_MODULE_ERROR){
        $this->origin = $origin;
    }
}

?>