<?php
namespace PhpSarkozy\Http;



use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\core\api\SarkoView as SarkoView;

#[SarkozyModule(SarkozyModule::HTTP_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";

    private $templateModule;

    public function __construct(array $controllers, array $modules){
        //TODO: true ref when templating server commited
        $this->templateModule = array_key_exists("SarkozyModule::TEMPLATE_MODULE", $modules) ?
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

        return new Request($method, $path, $headers, $body);
    }

    function append_response(Request $srcRequest, $controllerResponse): Request{
        //TODO: real response detection
        $isTemplate = $this->templateModule !== null && $controllerResponse instanceof SarkoView;
        if ($isTemplate){
            return $this->templateModule->getTemplateResponse($controllerResponse->get_view_reference(), $controllerResponse->get_view_args());
        }
        //TODO: response if not template
    }
}


?>