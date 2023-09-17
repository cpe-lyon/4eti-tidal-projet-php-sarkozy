<?php
require_once 'vendor/autoload.php';

use PhpSarkozy\core\api\SarkoView;
use PhpSarkozy\core\api\SarkoJson;
use PhpSarkozy\core\SarkozyServer;
use PhpSarkozy\core\attributes\Sarkontroller;

use PhpSarkozy\Http\HttpModule;
echo "Enabled ". HttpModule::NAME . "\n";
use PhpSarkozy\LeTempsDesTemplates\LeTempsDesTemplatesModule;
use PhpSarkozy\LeTempsDesTemplates\Template;

echo "Enabling ".LeTempsDesTemplatesModule::MODULE_NAME."\n";

echo "Starting PHP Sarkozy...\n";


use PhpSarkozy\LeTempsDesTemplates\LtdtView;

#[Sarkontroller]
class MonController{
    function index(){
        return new LtdtView("/temps.html");
    }
}

#[Sarkontroller]
class MonController2{
}
$server = new SarkozyServer();
$server->run();

?>