<?php

use PhpSarkozy\core\attributes\Sarkontroller;

use PhpSarkozy\HttpRouting\attributes\HttpPath;
use PhpSarkozy\HttpRouting\attributes\HttpInPath;
use PhpSarkozy\Http\attributes\HttpEnforceHeader;
use PhpSarkozy\Http\attributes\HttpProduces;
use PhpSarkozy\LeTempsDesTemplates\LtdtView;

#[Sarkontroller]
class Test{

  #[HttpPath("/")]
  public function index(){
    return array(
      "Hello" => "World"
    );
  }
  
  #[HttpPath("/[id]")]
  public function salut( $data , #[HttpInPath("id")] $id ){
    return new LtdtView("index.ltdt", ["title" => "Titre inserer grace au LTDT", "id"=>$id, "data" => $data]);
  }

  #[HttpPath("/json")]
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

  #[HttpPath("/favicon.ico")]
  #[HttpProduces("image/x-icon")]
  #[HttpEnforceHeader("Content-Disposition","inline; filename=\"favicon.ico\"")]
  #[HttpEnforceHeader('Access-Control-Allow-Origin', '*')]
  #[HttpEnforceHeader("Content-Security-Policy", "default-src *")]
  public function faviconico(){
      $data = file_get_contents("resources/favicon.ico");
      return $data;
  }
}

?>
