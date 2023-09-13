<?php
require 'vendor/autoload.php';

use core\SarkozyServer;
use attributes\Sarkontroller;
use attributes\SarkozyModule;
use PhpSarkozy\LeTempsDesTemplates\LeTempsDesTemplatesModule;
use PhpSarkozy\LeTempsDesTemplates\Template;

echo "Enabling ".LeTempsDesTemplatesModule::MODULE_NAME."\n";

echo "Starting PHP Sarkozy\n";

#[Sarkontroller]
class MonController{
}

#[Sarkontroller]
class MonController2{
}

#[SarkozyModule(SarkozyModule::HTTP_MODULE)]
class MonModule{

    function __construct(array $controllers)
    {
        var_dump($controllers);
    }

}
$server = new SarkozyServer();
$server->run();

?>