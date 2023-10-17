<?php

namespace PhpSarkozy\Http;

use PhpSarkozy\core\api\Request as Request;
use PhpSarkozy\core\api\SarkoView;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\Http\attributes\HttpProduces;
use PhpSarkozy\Http\models\HttpResponseType;

class HttpParser{

    private $template_module;

    function __construct($template_module){
        $this->template_module = $template_module;
    }

    private function guess_response_type($controller_response): HttpResponseType{
        if($this->template_module != null && $controller_response instanceof SarkoView){
            return HttpResponseType::TEMPLATE;
        }
        if (is_string($controller_response)){
            return HttpResponseType::STRING_RAW;
        }
        return HttpResponseType::JSON;
    }

    function get_response($controller_response, ?HttpProduces $produces): HttpResponse{
        $type = $this->guess_response_type($controller_response);
        switch ($type) {
            case HttpResponseType::TEMPLATE:
                /**
                 * @var SarkoView $sarko_view
                 */
                $sarko_view = $controller_response;
                return $this->template_module->get_template_response($sarko_view->get_view_reference(), $sarko_view->get_view_args());
            case HttpResponseType::STRING_RAW:
                $res = HttpResponse::createOK($controller_response);
                $res->set_content_type($produces == null ? "text/plain" : $produces->get_content_type());
                return $res;
            default:
            case HttpResponseType::JSON:
                $res = HttpResponse::createOK(json_encode($controller_response));
                $res->set_content_type($produces == null ? "application/json" : $produces->get_content_type());
                return $res;
        }
    }

    function get_request($client) : HttpRequest {
        $request_line = fgets($client);

        // Http method and path extraction
        list($method, $path, $protocol) = explode(' ', trim($request_line));

        // Read Headers
        $headers = [];
        while ($header = trim(fgets($client))) {
            list($name, $value) = preg_split('/:\s+/', $header, 2);
            $headers[$name] = $value;
        }
    
        // Read Body
        $body = '';
        if (isset($headers['Content-Length'])) {
            $content_length = (int)$headers['Content-Length'];
            $body = fread($client, $content_length);
        }

        return new HttpRequest($client, $method, $path??"/", $headers, $body);
    }

    private function get_message_for_status(int $status){
        switch ($status) {
            case 200: return "OK";
            case 404: return "Not found";
            default: return "";
        }
    }

    function get_raw_response(HttpRequest $request): String{
        $res = "";
        /**
         * @var HttpResponse
         */
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