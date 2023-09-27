<?php

namespace PhpSarkozy\Http\api;

use PhpSarkozy\core\api\Response;

class HttpResponse extends Response{

    public function __construct(string $body)
    {
        parent::__construct(array(
            "body" => $body,
            "headers" => array()
        ));
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
        $this->metadata["headers"][$key] = $value; 
    }

    public function get_headers(){
        return $this->metadata["headers"];
    }

    public function get_body(){
        return $this->metadata["body"];
    }
}
?>