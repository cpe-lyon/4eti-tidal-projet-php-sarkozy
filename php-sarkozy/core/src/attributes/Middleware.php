<?php

namespace PhpSarkozy\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Middleware{
    private int $priority; 

    public function __constructor(int $priority){
        $this->priority;
    }

    public function get_priority(): int{
        return $this->priority;
    }

    public function set_priority(int $priority){
        $this->priority = $priority;
    }
}


?>