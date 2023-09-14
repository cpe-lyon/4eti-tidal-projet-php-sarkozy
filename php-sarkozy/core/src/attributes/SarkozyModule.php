<?php

namespace PhpSarkozy\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class SarkozyModule{
    const PROTOCOL_MODULE =1;
    public function __constructor(int $module_flag){
    }
}


?>