<?php

namespace PhpSarkozy\Http;

use PhpSarkozy\core\api\Request as Request;

class HttpParser{

    function __construct(){

    }

    function get_request($client) : Request {
        $request_line = fgets($client);

        // Http method and path extraction
        list($method, $path, $protocol) = explode(' ', trim($request_line));

        // Read Headers
        $headers = [];
        while ($header = trim(fgets($client))) {
            list($name, $value) = explode(':', $header, 2);
            $headers[$name] = $value;
        }
    
        // Read Body
        $body = '';
        if (isset($headers['Content-Length'])) {
            $content_length = (int)$headers['Content-Length'];
            $body = fread($client, $content_length);
        }

        return new Request($client, $method, $path, $headers, $body);
    }

    private function get_message_for_status(int $status){
        switch ($status) {
            case 200: return "OK";
            case 404: return "Not found";
            default: return "";
        }
    }

    function get_raw_response(Request $request): String{
        $res = "";
        $response = $request->get_response();
        
        foreach($response->get_headers() as $key => $value){
            if($key == "HTTP"){
                $res .= "HTTP/2 ".$value." ".$this->get_message_for_status($value)."\r\n";
            }else{
                $res .= $key.": ".$value."\r\n";
            }
        }
        $res .= "\r\n";

        $res .= $response->get_body();

        return $res;
    }

}

?>