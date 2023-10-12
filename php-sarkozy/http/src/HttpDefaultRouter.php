<?php

namespace PhpSarkozy\Http;

use HttpRouter;
use HttpRouterInterface;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\models\HttpRouterInterface as ModelsHttpRouterInterface;
use PhpSarkozy\Http\models\HttpSarkontrollerRequest;
use PhpSarkozy\Http\utils\HttpMethodsEnum;

/**
 * A router with very limited features for tests while RouterModule is not activated
 */
class HttpDefaultRouter implements ModelsHttpRouterInterface{
    
    public function get_call(string $path, HttpMethodsEnum $method, array $default_args=array()): SarkontrollerRequest{
        
        $path_parts = preg_split("/\?/", preg_replace('/[\/\.]/', "", $path), 1);
        $clean_path = $path_parts[0];
        $raw_args = count($path_parts) > 1 ? $path_parts[1]: null;
        $args = $default_args;
        if ($raw_args != null){
            parse_str($raw_args, $args);
        }
        return new SarkontrollerRequest(0, $clean_path, $args);

    }

}

?>