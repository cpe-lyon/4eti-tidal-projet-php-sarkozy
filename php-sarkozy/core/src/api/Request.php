<?php
namespace PhpSarkozy\core\api;

/*
 * Resquest Class
 */ 

class Request {
    private string $method;
    private string $uri;
    private array $headers;
    private string $body;

    public function __construct(string $method, string $uri, array $headers, string $body) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
    }


    function get_method() : string {
        return $this->method;
    }

    function get_uri() : string {
        return $this->uri;
    }

    function get_headers() : array {
        return $this->headers;
    }

    function get_body() : string {
        return $this->body;
    }
}

?>