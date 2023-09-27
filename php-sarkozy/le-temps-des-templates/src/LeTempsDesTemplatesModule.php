<?php

namespace PhpSarkozy\LeTempsDesTemplates;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\api\Response;

#[SarkozyModule(SarkozyModule::TEMPLATE_MODULE)]
class LeTempsDesTemplatesModule
{

    private $path = "";
    const MODULE_NAME = "LE-TEMPS-DES-TEMPLATES";

    function __construct(string $path)
    {
        //TODO @ruben.clerc: loading
        $this->path = getcwd()."/views";
    }


    function get_template_response(string $templatename, array $args): Response{

        $file_path = $this->path . "/" . $templatename;
        
        $template = new Template($file_path);
        $template->array_assign($args);
        $compiled_html = $template->render();

        $response = new Response($compiled_html);
        $response->set_code(200);

        return $response;
    }

}

?>