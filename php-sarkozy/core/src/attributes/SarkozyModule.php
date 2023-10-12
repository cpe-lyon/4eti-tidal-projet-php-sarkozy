<?php

namespace PhpSarkozy\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class SarkozyModule{
    const PROTOCOL_MODULE =1;
    //These are submodules for the protocol module
    
    //Module meant to help Protocol module to return data efficiently using other files
    const TEMPLATE_MODULE =2;
    
    //Module meant to help Protocol module to determine the controller call
    const ROUTING_MODULE = 3;
    
    const MIDDLEWARE_MODULE = 4;

    public function __constructor(int $module_flag){
    }
}


?>