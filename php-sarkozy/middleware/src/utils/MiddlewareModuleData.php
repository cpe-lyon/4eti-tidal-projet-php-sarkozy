<?php

namespace PhpSarkozy\Middleware\utils;

use PhpSarkozy\core\api\MiddlewareData;

class MiddlewareModuleData extends MiddlewareData{

    private array $instances;

    function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    function get_instances(): array{
        return $this->instances;
    }

}