<?php

namespace PhpSarkozy\Http\api;

use PhpSarkozy\core\api\Request;
use PhpSarkozy\Http\attributes\HttpProduces;

class HttpRequest extends Request{
    public function __construct($client, string $method, string $uri, array $headers, string $body) {
        parent::__construct($client, array(
            "_protocol_module" => array(
                "method" => $method,
                "uri" => $uri,
                "headers" => $headers,
                "body" => $body
            ) 
        ));
    }

    private function get_http_metadata(){
        return $this->get_metadata()["_protocol_module"];
    }

    function get_method() : string {
        return $this->get_http_metadata()["method"];
    }

    function get_uri() : string {
        return $this->get_http_metadata()["uri"];
    }

    function get_headers() : array {
        return $this->get_http_metadata()["headers"];
    }

    function get_body() : string {
        return $this->get_http_metadata()["body"];
    }

    function get_content_type($default=null): string{
        return isset($this->get_headers()['Content-Type']) ? $this->get_headers()['Content-Type'] : $default;
    }
}


?>