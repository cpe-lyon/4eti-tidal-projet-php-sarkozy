<?php

namespace PhpSarkozy\routing;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Http\models\HttpRouterInterface;
use PhpSarkozy\Http\models\HttpRoutingModuleInterface;
use PhpSarkozy\routing\HttpRouter;

#[SarkozyModule(SarkozyModule::ROUTING_MODULE)]
class HttpRoutingModule implements HttpRoutingModuleInterface{

    function create_router(array $controllers) :HttpRouterInterface{
        return new HttpRouter($controllers);
    }
}