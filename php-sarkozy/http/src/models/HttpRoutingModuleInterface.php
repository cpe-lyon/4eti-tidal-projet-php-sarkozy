<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\Http\api\HttpResponse;
use PhpSarkozy\Http\models\HttpRouterInterface;

interface HttpRoutingModuleInterface{
    /**
     * @param HttpControllerRecord[] $controllers
     */
    function create_router(array $controllers) :HttpRouterInterface;
}