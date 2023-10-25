<?php

require_once 'vendor/autoload.php';

new SarkozyLoader(__DIR__."/../../controllers");
new SarkozyLoader(__DIR__."/../../middlewares");
new SarkozyLoader(__DIR__."/../modules");

new SarkozyLoader(__DIR__."/../../controllers");
new SarkozyLoader(__DIR__."/../modules");

$server = new SarkozyServer(views_path:__DIR__."/../../views");
$server->run();
?>
