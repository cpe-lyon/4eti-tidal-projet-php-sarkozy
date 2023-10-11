<?php

use PhpSarkozy\Http\HttpModule;
use PhpSarkozy\LeTempsDesTemplates\LeTempsDesTemplatesModule;
use PhpSarkozy\HttpRouting\HttpRoutingModule;

echo "Enabled ".HttpModule::NAME."\n";
echo "Enabled ".HttpRoutingModule::NAME."\n";
echo "Enabled ".LeTempsDesTemplatesModule::MODULE_NAME."\n";

?>