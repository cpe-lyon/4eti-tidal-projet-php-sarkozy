<?php

namespace PhpSarkozy\Http\api;

use PhpSarkozy\core\api\Request;
use PhpSarkozy\Http\attributes\HttpProduces;

class HttpRequest extends Request{
    public function __construct($client, string $method, string $uri, array $headers, string $body) {
        parent::__construct($client, array(
            "method" => $method,
            "uri" => $uri,
            "headers" => $headers,
            "body" => $body,
            "attributes" => array(new HttpProduces("text/javascript")) // TODO : @Webjosse manage http attributes 
        ));
    }

    function get_method() : string {
        return $this->get_metadata()["method"];
    }

    function get_uri() : string {
        return $this->get_metadata()["uri"];
    }

    function get_headers() : array {
        return $this->get_metadata()["headers"];
    }

    function get_body() : string {
        return $this->get_metadata()["body"];
    }

    function get_attributes() : array {
        return $this->get_metadata()["attributes"];
    }
}


?>