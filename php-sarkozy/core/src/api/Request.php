<?php
namespace PhpSarkozy\core\api;

/*
 * Resquest Class
 */ 

class Request {
    private $client;
    private string $method;
    private string $uri;
    private array $headers;
    private string $body;
    private Response $response;

    public function __construct($client, string $method, string $uri, array $headers, string $body) {
        $this->client = $client;
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

    function get_client(){
        return $this->client;
    }

    function get_response(){
        return $this->response;
    }

    function set_response(Response $response){
        $this->response = $response;
    }
}

?>