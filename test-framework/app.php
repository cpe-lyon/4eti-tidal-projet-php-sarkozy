<?php

require 'vendor/autoload.php';

use core\SarkozyServer;
use attributes\Sarkontroller;
use attributes\SarkozyModule;

echo "Starting PHP Sarkozy";

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