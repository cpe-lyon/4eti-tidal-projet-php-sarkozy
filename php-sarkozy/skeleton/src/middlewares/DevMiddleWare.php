<?php

use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\attributes\Middleware;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\Middleware\api\MiddlewareInterface;


#[Middleware]
class DevMiddleWare implements MiddlewareInterface{


    private $dev_mode = false;
    private HttpRequest $request;

    function intercept_request(Request $req): Request{
        if (!$req instanceof HttpRequest){
            throw new Exception("Not Http");
        }
        
        
        

        if (str_starts_with($req->get_uri(), "/dev/")){
            $uri = substr($req->get_uri(), 4);
            $http_req = new HttpRequest($req->get_client(), $req->get_method(), $uri, $req->get_headers(), $req->get_body());
            $this->dev_mode = true;
            $this->request = $http_req;
            return $http_req;
        }

        return $req;
    }


    const DEV_INFO_LAYOUT = <<<DEV_INFO
    <style>
        #framework-sarkozy-info-u0hz_9{
            display: flex;
            position: fixed;
            bottom: 0px;
            left: 0px;
            width: 100%;
            padding: 1em;
            background: #ffff00;
        }
    </style>
    <section id="framework-sarkozy-info-u0hz_9">
        [[content]]
    </section>
    DEV_INFO;

    function get_info(){
        $content = "";
        $arr = $this->request->get_headers();
        foreach($arr as $k=>$v){
            $content .= "$k: $v<br/>";
        }

        return str_replace("[[content]]",$content, DevMiddleWare::DEV_INFO_LAYOUT);
    }

    function intercept_response(Response $response): Response{
        if (!$response instanceof HttpResponse || !$this->dev_mode){
            return $response;
        }

        $ctype = $response->get_content_type();

        if (str_starts_with($ctype, 'text/html')){
            $body = $response->get_body();
            $get_info = $this->get_info();
            $response->set_body(preg_replace('/<\\/body/' ,"$get_info</body",$body, 1));
            $response->set_content_length(strlen($response->get_body()));
        }

        return $response;
    }
}