<?php
namespace PhpSarkozy\core\api;

/*
 * Response Class
 */ 
class Response {

    protected array $metadata;
    private array $headers = array();
    private string $body;

    public function __construct($metadata) {
        $this->metadata = $metadata;
    }

    public function get_metadata(){
        return $this->metadata;
    }

}

?>