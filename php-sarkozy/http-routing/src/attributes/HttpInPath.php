<?php

namespace PhpSarkozy\routing\attributes;

use PhpSarkozy\Http\attributes\HttpAttributeInterface;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class HttpInPath implements HttpAttributeInterface{

    public readonly string $src_name;

    function __construct($src_name){
        $this->src_name = $src_name;
    }
}
