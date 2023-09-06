<?php

/*
 * Resquest Class
 */ 

class Request {
    private $get;
    private $post;
    private $server;

    public function __construct($get, $post, $server) {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }


}

?>