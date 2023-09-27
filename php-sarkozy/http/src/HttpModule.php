<?php
namespace PhpSarkozy\Http;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\api\SarkoError as ApiSarkoError;
use PhpSarkozy\core\api\SarkoView as SarkoView;
use PhpSarkozy\core\api\SarkoJson as SarkoJson;
use PhpSarkozy\core\api\SarkontrollerRequest;

#[SarkozyModule(SarkozyModule::PROTOCOL_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";

    private $template_module;

    private HttpParser $parser;

    public function __construct(array $controllers, array $modules){
        //TODO: true ref when templating server commited
        $this->template_module = array_key_exists("SarkozyModule::TEMPLATE_MODULE", $modules) ?
            $modules["SarkozyModule::TEMPLATE_MODULE"] : null;
        //TO-DO

        $this->parser = new HttpParser();

    }

    function get_protocol(): string{
        return "http";
    }

    function get_request($client) : Request {
        return $this->parser->get_request($client);
    }

    function get_raw_response(Request $request): string{
        return $this->parser->get_raw_response($request);
    }

    public function get_call(Request $request):SarkontrollerRequest
    {
        $path = $request->get_uri();
        return HttpDefaultRouter::get_call($path);
    }

    private function handle_error(Request $request, $controller_response): Request{
        //TODO @theo.clere: error management
        $res = new HttpResponse("Error 404");
        $res->set_code(404);
        $request->set_response($res);
        return $request;
    }

    function handle_response(Request $request, $controller_response): Request{
        //TODO @theo.clere: response detection

        if($controller_response instanceof ApiSarkoError){
            return $this->handle_error($request, $controller_response);
        }

        $is_template = $this->template_module !== null && $controller_response instanceof SarkoView;
        if ($is_template){
            /**
             * @var SarkoView $sarko_view
             */
            $sarko_view = $controller_response;
            $request->set_response($this->template_module->get_template_response($sarko_view->get_view_reference()), $sarko_view->get_view_args());
        } else if ($controller_response instanceof SarkoJson){
    
            /**
             * @var SarkoJson $sarko_view
             */
            $sarko_json = $controller_response;
            $request->set_response($this->get_json_response($sarko_json));
        }
    
        return $request;
    }

    function get_json_response(SarkoJson $sarko_json): HttpResponse{
        $body = json_encode($sarko_json->get_value());
        $response = new HttpResponse($body);
        $response->set_code(200);
        $response->set_content_type("Application/json");
        $response->set_content_length(strlen($body));
        return $response;
    }

}


?>