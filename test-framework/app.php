<?php

require_once 'vendor/autoload.php';

use PhpSarkozy\core\SarkozyServer;
use PhpSarkozy\core\attributes\Sarkontroller;

use PhpSarkozy\Http\HttpModule;
echo "Enabled ". HttpModule::NAME . "\n";

echo "Starting PHP Sarkozy...\n";

#[Sarkontroller]
class MonController{
}

#[Sarkontroller]
class MonController2{
}

$server = new SarkozyServer();
$server->run();

?>