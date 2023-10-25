<?php
namespace PhpSarkozy\Http;

use HttpTemplateModuleInterface;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\api\HttpRequest;
use PhpSarkozy\Http\attributes\HttpEnforceHeader;
use PhpSarkozy\Http\models\HttpControllerMethodRecord;
use PhpSarkozy\Http\models\HttpControllerRecord;
use PhpSarkozy\Http\models\HttpRouterInterface;
use PhpSarkozy\Http\models\HttpSarkontrollerRequest;
use PhpSarkozy\Http\utils\HttpAttributesUtils;
use PhpSarkozy\Http\utils\HttpMethodsEnum;
use PhpSarkozy\Http\utils\HttpMethodUtils;

#[SarkozyModule(SarkozyModule::PROTOCOL_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";
    /**
     * @var HttpTemplateModuleInterface
     */
    private $template_module;

    private HttpParser $parser;

    private HttpRouterInterface $router;

    /**
     * @var HttpControllerRecord[]
     */
    private array $controllers;

    public function __construct(array $controllers, array $modules){
        $this->template_module = array_key_exists(SarkozyModule::TEMPLATE_MODULE, $modules) ?
            $modules[SarkozyModule::TEMPLATE_MODULE] : null;
        //TO-DO

        $this->controllers = array_map(
            fn($c) => new HttpControllerRecord($c),
            $controllers
        ); 

        /**
         * 
         */
        $router_module = array_key_exists(SarkozyModule::ROUTING_MODULE, $modules) ?
        $modules[SarkozyModule::ROUTING_MODULE] : null;

        if ($router_module == null){
            $this->router = new HttpDefaultRouter();
        }else{
            $this->router = $router_module->create_router($this->controllers);
        }

        $this->parser = new HttpParser($this->template_module);

    }

    function get_protocol(): string{
        return "http";
    }

    function get_request($client) : HttpRequest {
        return $this->parser->get_request($client);
    }

    function get_raw_response(HttpRequest $request): string{
        $this->check_request($request);
        return $this->parser->get_raw_response($request);
    }

    private function check_request(Request $req){
        if (! ($req instanceof HttpRequest)){
            throw new \Exception("Request is not HTTP");
        }
        if ($req->get_response() !== null && !($req->get_response() instanceof HttpResponse)){
            throw new \Exception("Response is not HTTP");
        }
    }


    private function get_default_args(HttpRequest $request){
        $method = HttpMethodUtils::parse_method($request->get_method());
        if (!HttpMethodUtils::has_body($method)){
            return array();
        }
        $args = array();
        $body = $request->get_body();
        $ctype = $request->get_content_type('text/plain');
        switch ($ctype) {
            case 'application/x-www-form-urlencoded':
                parse_str($body, $args);
                break;
            case 'application/json':
                $args = json_decode($body, flags:JSON_OBJECT_AS_ARRAY);
                break;
            default:
                $args = array("body" => $body);
                break;
        }
        return $args;
    }

    public function get_call(HttpRequest $request):SarkontrollerRequest
    {
        $this->check_request($request);
        $path = $request->get_uri();
        return $this->router->get_call(
            $path,
            HttpMethodUtils::parse_method($request->get_method()),
            $this->get_default_args($request)
        );
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

    function handle_response(HttpRequest $request, $controller_response): Request{
        //TODO @theo.clere: response detection
        $this->check_request($request);

        if($controller_response instanceof \Exception){
            return $this->handle_error($request, $controller_response);
        }

        $produces = HttpAttributesUtils::get_http_produces($request, $this->controllers);

        $response = $this->parser->get_response($controller_response, $produces);

        /**
         * @var HttpEnforceHeader[]
         */
        $headers = HttpAttributesUtils::filter_attrs($request, $this->controllers, fn($a) => $a instanceof HttpEnforceHeader);
        foreach($headers as $h){
            $h->add_header($response);
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