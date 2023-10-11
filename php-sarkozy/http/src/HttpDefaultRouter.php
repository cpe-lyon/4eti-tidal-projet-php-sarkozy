<?php

namespace PhpSarkozy\Http;

use HttpRouter;
use HttpRouterInterface;
use PhpSarkozy\core\api\SarkontrollerRequest;
use PhpSarkozy\Http\models\HttpRouterInterface as ModelsHttpRouterInterface;
use PhpSarkozy\Http\utils\HttpMethodsEnum;

/**
 * A router with very limited features for tests while RouterModule is not activated
 */
class HttpDefaultRouter implements ModelsHttpRouterInterface{
    
    public function get_call(string $path, HttpMethodsEnum $method): SarkontrollerRequest{
        
        $path_parts = preg_split("/\?/", preg_replace('/[\/\.]/', "", $path), 1);
        $cleanPath = $path_parts[0];
        $rawArgs = count($path_parts) > 1 ? $path_parts[1]: null;
        $args = array();
        if ($rawArgs != null){
            parse_str($rawArgs, $args);
        }
        echo $cleanPath;
        return new SarkontrollerRequest(0, $cleanPath, $args);

    }

}

?>