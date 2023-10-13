<?php

namespace PhpSarkozy\LeTempsDesTemplates;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\Http\models\HttpTemplateModuleInterface;

#[SarkozyModule(SarkozyModule::TEMPLATE_MODULE)]
class LeTempsDesTemplatesModule implements HttpTemplateModuleInterface
{

    private $path = "";
    const MODULE_NAME = "LE-TEMPS-DES-TEMPLATES";

    function __construct(string $path=null)
    {

        if ($path == null){
            $this->path = getcwd()."/views";
        }else if ( str_ends_with($path, '/') ){
            $this->path = substr($path, 0, strlen($path)-1);
        }else{
            $this->path = $path;
        }
        
    }


    function get_template_response(string $templatename, array $args): HttpResponse{

        if ( str_starts_with($templatename, '/') ){
            $templatename = substr($templatename, 1);
        }
        $file_path = $this->path . "/" . $templatename;

        $template = new Template($file_path);
        $template->array_assign($args);
        $compiled_html = $template->render();

        $response = new HttpResponse($compiled_html);
        $response->set_code(200);

        return $response;
    }

}

?>