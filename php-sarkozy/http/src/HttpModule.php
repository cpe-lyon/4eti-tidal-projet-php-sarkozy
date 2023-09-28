<?php
namespace PhpSarkozy\Http;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\core\api\SarkoView as SarkoView;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\utils\HttpAttributesUtils;

#[SarkozyModule(SarkozyModule::PROTOCOL_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";

    private $template_module;

    private HttpParser $parser;

    public function __construct(array $controllers, array $modules){
        $this->template_module = array_key_exists(SarkozyModule::TEMPLATE_MODULE, $modules) ?
            $modules[SarkozyModule::TEMPLATE_MODULE] : null;
        //TO-DO

        $this->parser = new HttpParser();

    }

    function get_protocol(): string{
        return "http";
    }

    function get_request($client) : HttpRequest {
        return $this->parser->get_request($client);
    }

    function get_raw_response(HttpRequest $request): string{
        return $this->parser->get_raw_response($request);
    }

    public function get_call(HttpRequest $request):SarkontrollerRequest
    {
        $path = $request->get_uri();
        return HttpDefaultRouter::get_call($path);
    }


    private function handle_error(HttpRequest $request, \Exception $error): HttpRequest{
        $res = new HttpResponse($error->getMessage());
        if ($error->getCode() != 0){
            $res->set_code($error->getCode());
        }else{
            $res->set_code(500);
        }
        $request->set_response($res);
        return $request;
    }

    function handle_response(HttpRequest $request, $controller_response): HttpRequest{
        /**
         * @var HttpResponse $response
         */
        $response;

        if($controller_response instanceof \Exception){
            return $this->handle_error($request, $controller_response);
        }

        $is_template = $this->template_module !== null && $controller_response instanceof SarkoView;
        if ($is_template){
            /**
             * @var SarkoView $sarko_view
             */
            $sarko_view = $controller_response;
            $response = $this->template_module->get_template_response($sarko_view->get_view_reference(), $sarko_view->get_view_args());
        } else {
            if(HttpAttributesUtils::get_http_produces($request) != null) {
                $produces = HttpAttributesUtils::get_http_produces($request);
                $response = $this->get_response($controller_response, $produces->get_content_type());
            } else if(is_string($controller_response)){ 
                $response = $this->get_response($controller_response, "text/plain");
            } else {
                $response = $this->get_response($controller_response, "application/json");
            }
        }
    
        $request->set_response($response);
        return $request;
    }

    function get_response($value, String $content_type): HttpResponse{
        $body = json_encode($value);
        $response = new HttpResponse($body);
        $response->set_code(200);
        $response->set_content_type($content_type);
        $response->set_content_length(strlen($body));
        return $response;
    }

}


?>