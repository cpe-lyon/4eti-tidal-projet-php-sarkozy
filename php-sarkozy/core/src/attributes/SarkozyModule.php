<?php

namespace PhpSarkozy\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class SarkozyModule{
    const MIDDLEWARE_MODULE = 4;
    const TEMPLATE_MODULE = 2;
    const PROTOCOL_MODULE = 1;
    public function __constructor(int $module_flag){
    }
}


?>