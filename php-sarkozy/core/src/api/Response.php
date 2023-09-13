<?php
namespace PhpSarkozy\core\api;

/*
 * Response Class
 */ 
class Response {
    private array $headers = array();
    private string $body;

    public function __construct($body) {
        $this->body = $body;
    }


    public function set_code($code){
        $this->set_header("HTTP", $code);
    }

    public function set_content_type($value){
        $this->set_header("Content-Type", $value);
    }

    public function set_content_length($value){
        $this->set_header("Content-Length", $value);
    }

    public function set_header(string $key, string $value){
        $this->headers[$key] = $value; 
    }

    public function get_headers(){
        return $this->headers;
    }

    public function get_body(){
        return $this->body;
    }

}

?>