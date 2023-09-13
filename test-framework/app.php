<?php

require_once 'vendor/autoload.php';

use PhpSarkozy\core\api\SarkoView;
use PhpSarkozy\core\api\SarkoJson;
use PhpSarkozy\core\SarkozyServer;
use PhpSarkozy\core\attributes\Sarkontroller;

use PhpSarkozy\Http\HttpModule;
echo "Enabled ". HttpModule::NAME . "\n";

echo "Starting PHP Sarkozy...\n";

class FakeView implements SarkoView{
    function get_view_reference(): string
    {
        return "ok";
    }

    function get_view_args(): array
    {
        return array();
    }
}


class FakeJson implements SarkoJson{
    function get_value(): array{
        $array = array (
            "toto",
            24,
            "fruits"  => array("a" => "orange", "b" => "banana", "c" => "apple"),
            "numbers" => array(1, 2, 3, 4, 5, 6),
            "holes"   => array("first", 5 => "second", "third")
        );
        return $array;
    }
}

#[Sarkontroller]
class MonController{
    public function ltdt($title="Mon Titre")
    {
        return new FakeView();
    }
    public function json(){
        return new FakeJson();
    }
}

$server = new SarkozyServer();
$server->run();

?>