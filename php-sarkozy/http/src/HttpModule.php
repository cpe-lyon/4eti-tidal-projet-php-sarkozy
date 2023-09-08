<?php
namespace PhpSarkozy\Http;



use attributes\SarkozyModule;
use api\Request;

#[SarkozyModule(SarkozyModule::HTTP_MODULE)]
final class HttpModule{

    final const NAME = "HTTP-MODULE";

    public function __construct(array $controllers){
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
}


?>