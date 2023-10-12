<?php

namespace PhpSarkozy\HttpRouting;

use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Http\models\HttpRouterInterface;
use PhpSarkozy\Http\models\HttpRoutingModuleInterface;
use PhpSarkozy\HttpRouting\HttpRouter;

#[SarkozyModule(SarkozyModule::ROUTING_MODULE)]
class HttpRoutingModule implements HttpRoutingModuleInterface{

    final const NAME = "HTTP-ROUTING-MODULE";

    function create_router(array $controllers) :HttpRouterInterface{
        return new HttpRouter($controllers);
    }
}