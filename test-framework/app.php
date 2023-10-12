<?php

require_once 'vendor/autoload.php';

use PhpSarkozy\core\api\SarkoView;
use PhpSarkozy\core\SarkozyServer;
use PhpSarkozy\core\attributes\Sarkontroller;
use PhpSarkozy\Http\attributes\HttpEnforceHeader;
use PhpSarkozy\Http\attributes\HttpProduces;

use PhpSarkozy\Http\HttpModule;
echo "Enabled ". HttpModule::NAME . "\n";
use PhpSarkozy\LeTempsDesTemplates\LeTempsDesTemplatesModule;
echo "Enabling ".LeTempsDesTemplatesModule::MODULE_NAME."\n";

echo "Starting PHP Sarkozy...\n";

use PhpSarkozy\LeTempsDesTemplates\LtdtView;

class FakeView implements SarkoView{
    function get_view_reference(): string
    {
        return "temps.ltdt";
    }

    function get_view_args(): array
    {
        return array(
            "titre" => "SarkoTest",
            "contenu" => "Bonjour, je suis le SarkoTest et j'aime quand ça marche"
        );
    }
}

#[Sarkontroller]
class MonController{
    public function ltdt($title="Mon Titre")
    {
        return new FakeView();
    }



    #[HttpEnforceHeader("Content-Security-Policy", "default-src 'self'")]
    public function json(){
        return array (
                "toto",
                24,
                "fruits"  => array("a" => "orange", "b" => "banana", "c" => "apple"),
                "numbers" => array(1, 2, 3, 4, 5, 6),
                "holes"   => array("first", 5 => "second", "third")
        );
    }

    #[HttpProduces("image/x-icon")]
    #[HttpEnforceHeader("Content-Disposition","inline; filename=\"favicon.ico\"")]
    #[HttpEnforceHeader('Access-Control-Allow-Origin', '*')]
    #[HttpEnforceHeader("Content-Security-Policy", "default-src *")]
    public function faviconico(){
        $data = file_get_contents("./favicon.ico");
        return $data;
    }

    public function string(){
        return "test string";
    }
}

$server = new SarkozyServer();
$server->run();

?>