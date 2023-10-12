<?php
namespace PhpSarkozy\core\api;

/*
 * Resquest Class
 */ 

class Request {
    private $client;

    private array $metadata;

    
    private Response $response;

    public ?SarkontrollerRequest $call = null;

    public function __construct($client, array $metadata) {
        $this->client = $client;
        $this->metadata = $metadata;
    }

    function get_metadata(){
        return $this->metadata;
    }

    function get_client(){
        return $this->client;
    }

    function get_response(){
        return isset($this->response) ? $this->response : null;
    }

    function set_response(Response $response){
        $this->response = $response;
    }
}

?>