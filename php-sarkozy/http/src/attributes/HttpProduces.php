<?php

namespace PhpSarkozy\Http\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class HttpProduces implements HttpAttributeInterface{
    private string $content_type;

    function __construct($content_type)
    {
        $this->content_type = $content_type;
    }

    function get_content_type(): string{
        return $this->content_type;
    }
}


?>