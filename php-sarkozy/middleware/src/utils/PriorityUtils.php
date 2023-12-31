<?php
namespace PhpSarkozy\Middleware\utils;

class PriorityUtils {

    final static function get_all_middlewares_priorized($middlewares): array{  
        $checked_middlewares = array();
        foreach($middlewares as $m){
            if(PriorityUtils::check_middleware($m)){
                array_push($checked_middlewares, $m);
            }
        }
  
        usort($checked_middlewares, fn($a,$b)=> $a["priority"] - $b["priority"]);
        return $checked_middlewares;
    }

    static function check_middleware($middleware){
        return in_array("PhpSarkozy\Middleware\api\MiddlewareInterface", $middleware["interfacesNames"]);
    }
}

?>