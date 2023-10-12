<?php

require_once 'vendor/autoload.php';

use PhpSarkozy\core\SarkozyServer;
use PhpSarkozy\core\SarkozyLoader;

new SarkozyLoader(__DIR__."/../../controllers");
new SarkozyLoader(__DIR__."/../modules");

$server = new SarkozyServer(views_path:__DIR__."/../../views");
$server->run();
?>