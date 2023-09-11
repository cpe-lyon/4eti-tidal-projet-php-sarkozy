<?php

namespace PhpSarkozy\LeTempsDesTemplates;

use attributes\SarkozyModule;
use api\Response;

#[SarkozyModule(SarkozyModule::TEMPLATE_MODULE)]
class LeTempsDesTemplatesModule
{

    const MODULE_NAME = "LE-TEMPS-DES-TEMPLATES";

    function __construct(string $path)
    {
        var_dump($path);
    }


    function getTemplateResponse(string $templatename, array $args): Response{
        $compiled_html = "";
        return new Response($compiled_html);
    }

}

?>