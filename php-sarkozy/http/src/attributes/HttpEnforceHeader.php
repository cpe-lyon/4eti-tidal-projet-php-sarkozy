<?php

namespace PhpSarkozy\Http\attributes;

use Attribute;
use PhpSarkozy\Http\api\HttpResponse;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final class HttpEnforceHeader implements HttpAttributeInterface{
    private string $key;
    private string $value;

    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    function add_header(HttpResponse $res){
        return $res->set_header($this->key, $this->value);
    }
}


?>