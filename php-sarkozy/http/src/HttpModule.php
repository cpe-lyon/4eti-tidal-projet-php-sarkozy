<?php
namespace PhpSarkozy\Http;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\api\SarkoView as SarkoView;
use PhpSarkozy\core\api\SarkoJson as SarkoJson;

#[SarkozyModule(SarkozyModule::HTTP_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";

    private $template_module;

    public function __construct(array $controllers, array $modules){
        //TODO: true ref when templating server commited
        $this->template_module = array_key_exists("SarkozyModule::TEMPLATE_MODULE", $modules) ?
            $modules["SarkozyModule::TEMPLATE_MODULE"] : null;
        //TO-DO

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

    function handle_response(Request $request): Request{
        //TODO: real response detection
        $controller_response = new SarkoJsonTest();


        $is_template = $this->template_module !== null && $controller_response instanceof SarkoView;
        if ($is_template){
            /**
             * @var SarkoView $sarko_view
             */
            $sarko_view = $controller_response;
            $request->set_response($this->template_module->get_template_response($sarko_view->get_view_reference()), $sarko_view->get_view_args());
            return $request;
        } else if ($controller_response instanceof SarkoJson){
    
            /**
             * @var SarkoJson $sarko_view
             */
            $sarko_json = $controller_response;
            $request->set_response($this->get_json_response($sarko_json));
        }
    
        return $request;
    }

    function get_json_response(SarkoJson $sarko_json): Response{
        $body = json_encode($sarko_json->get_value());
        $response = new Response($body);
        $response->set_code(200);
        $response->set_content_type("Application/json");
        $response->set_content_length(strlen($body));
        return $response;
    }

    function get_raw_response(Request $request): String{
        $res = "";
        $response = $request->get_response();
        
        foreach($response->get_headers() as $key => $value){
            $res .= $key.": ".$value."\r\n";
        }
        $res .= "\r\n";

        $res .= $response->get_body();

        return $res;
    }

}

class SarkoJsonTest implements SarkoJson{
    function get_value(): array{
        $array = array (
            "toto",
            24,
            "fruits"  => array("a" => "orange", "b" => "banana", "c" => "apple"),
            "numbers" => array(1, 2, 3, 4, 5, 6),
            "holes"   => array("first", 5 => "second", "third")
        );



        return $array;
    }
}


?>