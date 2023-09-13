<?php

namespace PhpSarkozy\LeTempsDesTemplates;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\api\Response;

#[SarkozyModule(SarkozyModule::TEMPLATE_MODULE)]
class LeTempsDesTemplatesModule
{

    const MODULE_NAME = "LE-TEMPS-DES-TEMPLATES";

    function __construct(string $path)
    {
        //TODO @ruben.clerc: loading
    }


    function getTemplateResponse(string $templatename, array $args): Response{
        $compiled_html = "";
        return new Response($compiled_html);
    }

}

?>