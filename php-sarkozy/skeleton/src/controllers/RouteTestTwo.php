<?php

use PhpSarkozy\core\attributes\Sarkontroller;
use PhpSarkozy\HttpRouting\attributes\HttpInPath;
use PhpSarkozy\HttpRouting\attributes\HttpPath;

#[Sarkontroller]
class RouteTestTwo{
  
  #[HttpPath("/[name]", "get")]
  public function route1(#[HttpInPath("name")] $slug){
    return "Page $slug";
  }

  #[HttpPath("/need")]
  public function route2($name){
    return "Need your $name";
  }
}

?>
