<?php
namespace PhpSarkozy\core\api;

/*
 * Resquest Class
 */ 

class Request {
    private $client;

    private array $metadata;

    
    private Response $response;

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
        return $this->response;
    }

    function set_response(Response $response){
        $this->response = $response;
    }
}

?>