<?php

namespace PhpSarkozy\LeTempsDesTemplates;

use PhpSarkozy\core\api\SarkoView;

class LtdtView implements SarkoView{

    private string $ref;
    private array $args;

    function __construct(string $ref, array $args=array())
    {
        $this->ref = $ref;
        $this->args = $args;
    }
    

    function get_view_args(): array
    {
        return $this->args;
    }

    function get_view_reference(): string
    {
        return $this->ref;
    }
}

?>