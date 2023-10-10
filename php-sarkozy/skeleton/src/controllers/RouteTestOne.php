<?php

use PhpSarkozy\core\attributes\Sarkontroller;
use PhpSarkozy\HttpRouting\attributes\HttpPath;

#[Sarkontroller]
class RouteTestOne{

  #[HttpPath("/", "get")]
  public function route1(){
    return 'Accueil';
  }

  #[HttpPath("/hello", "get")]
  public function route2($name="World"){
    return "Hello $name";
  }

}

?>
