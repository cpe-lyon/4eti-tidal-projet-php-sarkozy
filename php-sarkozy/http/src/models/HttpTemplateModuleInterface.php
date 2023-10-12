<?php


namespace PhpSarkozy\Http\models;

use PhpSarkozy\Http\api\HttpResponse;

interface HttpTemplateModuleInterface{
    function get_template_response(string $templatename, array $args): HttpResponse;
}