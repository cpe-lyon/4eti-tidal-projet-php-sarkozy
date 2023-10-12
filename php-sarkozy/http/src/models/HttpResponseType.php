<?php

namespace PhpSarkozy\Http\models;

enum HttpResponseType{
    case TEMPLATE;
    case STRING_RAW;
    case JSON;
}

?>