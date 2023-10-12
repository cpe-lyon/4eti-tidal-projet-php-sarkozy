<?php

use PhpSarkozy\Http\HttpModule;
use PhpSarkozy\LeTempsDesTemplates\LeTempsDesTemplatesModule;
use PhpSarkozy\HttpRouting\HttpRoutingModule;
use PhpSarkozy\Middleware\MiddlewareModule;

echo "Enabled ".HttpModule::NAME."\n";
echo "Enabled ".HttpRoutingModule::NAME."\n";
echo "Enabled ".LeTempsDesTemplatesModule::MODULE_NAME."\n";
echo "Enabled ".MiddlewareModule::NAME."\n";

?>