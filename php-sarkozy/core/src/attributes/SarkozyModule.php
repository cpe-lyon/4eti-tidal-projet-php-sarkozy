<?php

namespace PhpSarkozy\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class SarkozyModule{
    const HTTP_MODULE =1;
    const TEMPLATE_MODULE =2;
    public function __constructor(int $module_flag){
    }
}


?>